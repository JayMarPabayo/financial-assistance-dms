<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Requests\RequestRequest;
use App\Mail\SubmittedMail;
use App\Models\Request as RequestModel;
use App\Models\Requirement;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as AuthRequest;

use Barryvdh\DomPDF\Facade\Pdf;


Route::get('/home', function () {
    return view('index');
})->name('home');

Route::get('download/{filename}', function ($filename) {
    $filePath = storage_path("app/private/attachments/{$filename}");

    if (file_exists($filePath)) {
        return response()->download($filePath);
    } else {
        abort(404, 'File not found.');
    }
})->name('file.download');

Route::get('download-zip/{tracking}', function ($tracking) {
    $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();

    $attachments = $request->attachments ?? [];

    if (empty($attachments)) {
        abort(404, 'No files found.');
    }

    $zipFileName = "attachments-{$request->tracking_no}.zip";
    $zipPath = storage_path("app/private/attachments/{$zipFileName}");

    $zip = new ZipArchive;

    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach ($attachments as $attachment) {
            $filePath = storage_path('app/private/attachments/' . basename($attachment->file_path));
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($attachment->file_path));
            }
        }
        $zip->close();
    } else {
        abort(500, 'Could not create ZIP file.');
    }

    return response()->download($zipPath)->deleteFileAfterSend(true);
})->name('files.downloadZip');

Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return redirect()->route('home');
    })->name('home');

    Route::get('auth/login', function () {
        return view('auth.index');
    })->name('login');

    Route::post('auth/login', function (Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['username', 'password']);
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {

            $user = Auth::user();
            if ($user->block) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'credentials' => 'User is blocked'
                ]);
            }

            return redirect()->intended('/');
        } else {
            return redirect()->back()->withErrors([
                'credentials' => 'Invalid Credentials'
            ]);
        }
    })->name('auth.login');

    Route::resource('services', ServiceController::class)->only(['index', 'show']);

    Route::post('services/{service:slug}', function (RequestRequest $request, $slug) {

        $service = Service::where('slug', $slug)->firstOrFail();
        $data = $request->validated();

        $data['tracking_no'] = uniqid();

        $data['status'] = 'For review';

        $savedRequest = $service->requests()->create($data);

        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachment) {
                $filename =  $data['tracking_no'] . '-' . $attachment['file_path']->getClientOriginalName();
                $path = $attachment['file_path']->storeAs('attachments', $filename, 'private');
                $savedRequest->attachments()->create([
                    'requirement_id' => $attachment['requirement_id'],
                    'file_path' => $path,
                ]);
            }
        }

        Mail::to($savedRequest->email)->send(new SubmittedMail($savedRequest->tracking_no));

        $redirectUrl = route('applications.show') . '?search=' . $savedRequest->tracking_no;

        return redirect($redirectUrl)->with('prompt', "Please take note of your tracking code: {tracking_code}. You'll need this code to trace the progress of your request.");
    })->name('applications.post');

    Route::put('applications/{tracking}', function (Request $request) {
        $requestToUpdate = RequestModel::where('tracking_no', $request->input('tracking_no'))->firstOrFail();

        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'email' => 'nullable|email',
            'status' => 'nullable|string',

            'attachments' => 'sometimes|array',
        ]);
        $data['status'] = "For review";
        $requestToUpdate->update($data);

        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachment) {
                // Check if a new file is uploaded for this attachment
                if (isset($attachment['file_path']) && $attachment['file_path'] instanceof \Illuminate\Http\UploadedFile) {
                    $existingAttachment = $requestToUpdate->attachments()
                        ->where('requirement_id', $attachment['requirement_id'])
                        ->first();

                    // Generate the filename and store the file
                    $filename = $requestToUpdate->tracking_no . '-' . $attachment['file_path']->getClientOriginalName();
                    $path = $attachment['file_path']->storeAs('attachments', $filename, 'private');

                    // Update or create the attachment record
                    if ($existingAttachment) {
                        $existingAttachment->update([
                            'file_path' => $path,
                        ]);
                    } else {
                        $requestToUpdate->attachments()->create([
                            'requirement_id' => $attachment['requirement_id'],
                            'file_path' => $path,
                        ]);
                    }
                }
            }
        }


        $redirectUrl = route('applications.show') . '?search=' . $requestToUpdate->tracking_no;
        return redirect($redirectUrl);
    })->name('applications.update');


    Route::get('applications', [RequestController::class, 'show'])->name('applications.show');

    Route::get('applications/{tracking}', function (string $tracking) {
        $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
        return view('requests.client-edit', ['request' => $request]);
    })->name('applications.edit');
});

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        if (Auth::user()->role === "Staff") {
            return redirect()->route('requests.index');
        } else if (Auth::user()->role === "Administrator") {
            return redirect()->route('admin.index');
        } else {
            return redirect()->route('home');
        }
    });

    // For Staff
    Route::middleware('role:Staff')->group(function () {

        Route::resource('requests', RequestController::class);

        Route::get('transactions', function (Request $request) {
            $searchKey = $request->input('search');
            $service = $request->input('filter');
            $sort = $request->input('sort');
            $state = $request->input('state');

            $serviceId = Service::where('name', $service)->first()?->id;

            $requests = RequestModel::where('user_id', Auth::id())
                ->when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
                ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
                ->when($state, fn($query, $state) => $query->where('status', $state))
                ->orderBy($sort ?? 'id', 'asc')
                ->paginate(15);

            return view('transactions.index', [
                'requests' => $requests,
                'services' => Service::all(),
                'status' => RequestModel::$requestStatus
            ]);
        })->name('transactions.index');

        Route::get('transactions/{tracking}/edit', function (String $tracking) {
            $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
            return view('transactions.edit', ['request' => $request]);
        })->name('transactions.edit');

        Route::put('requests-submit', [RequestController::class, 'submit'])->name('applications.submit');

        Route::put('requests-submit-cancel', [RequestController::class, 'cancelSubmit'])->name('applications.submit.cancel');

        Route::put('requests-reject', [RequestController::class, 'reject'])->name('applications.reject');

        Route::put('requests-reject-cancel', [RequestController::class, 'cancelReject'])->name('applications.cancel.reject');
    });

    // For Admin
    Route::middleware('role:Administrator')->group(function () {

        Route::get('submissions', function (Request $request) {
            $searchKey = $request->input('search');
            $service = $request->input('filter');
            $sort = $request->input('sort');

            $serviceId = Service::where('name', $service)->first()?->id;

            $requests = RequestModel::where('status', "For schedule")->when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
                ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
                ->orderBy($sort ?? 'id', $sort == 'lastname' ? 'asc' : 'desc')
                ->paginate(15);
            return view('admin.index', ['requests' => $requests, 'services' => Service::all()]);
        })->name('admin.index');

        Route::get('submissions/{tracking}', function (String $tracking) {
            $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
            return view('admin.edit', ['request' => $request]);
        })->name('admin.edit');

        Route::get('schedules/export', function () {
            $schedules = Schedule::where('user_id', Auth::id())
                ->orderBy('date', 'asc')->get();

            return view('admin/report', ['schedules' => $schedules]);
        })->name('schedules.export');

        Route::get('financial-services', function () {
            $services = Service::all();
            return view('admin.services', ['services' => $services]);
        })->name('admin.services');

        Route::get('financial-services/create', function () {
            $requirementTypes = Requirement::getRequirementTypes();
            return view('admin.services-create', [
                'requirementTypes' => $requirementTypes,
            ]);
        })->name('admin.services.create');

        Route::put('transactions-submit-cancel', [RequestController::class, 'cancelSubmit'])->name('transactions.submit.cancel');

        Route::get('financial-services/{service:slug}', function ($slug) {
            $service = Service::where('slug', $slug)->firstOrFail();
            $requirementTypes = Requirement::getRequirementTypes();
            return view('admin.services-edit', ['service' => $service, 'requirementTypes' => $requirementTypes,]);
        })->name('admin.services.edit');

        Route::resource('schedules', ScheduleController::class);
        Route::resource('users', UserController::class);
        Route::resource('services', ServiceController::class)->only(['store', 'update', 'destroy']);
    });

    Route::get('profile', function () {
        return view('profile.index');
    })->name('profile.index');

    Route::put('profile', function (Request $request) {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => "required|string|max:255|unique:users,username,$user->id", // Ensure the username is unique except for the current user
        ]);

        /** @disregard [OPTIONAL CODE] [OPTIONAL DESCRIPTION] */
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);

        return redirect()->route('profile.index')->with('info_status', 'Profile updated successfully');
    })->name('profile.update');

    Route::put('profile/password', function (Request $request) {
        $user = Auth::user();
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|confirmed',
        ]);

        /** @disregard [OPTIONAL CODE] [OPTIONAL DESCRIPTION] */
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('status', 'Password updated successfully');
    })->name('profile.password');


    Route::delete('auth/logout', function () {
        Auth::logout();
        AuthRequest::session()->invalidate();
        AuthRequest::session()->regenerateToken();
        return redirect('/');
    })->name('auth.logout');
});
