<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Requests\RequestRequest;
use App\Models\Request as RequestModel;
use App\Models\Requirement;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as AuthRequest;

Route::get('/home', function () {
    return view('index');
})->name('home');

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


        // if ($request->has('files_path')) {
        //     $filePaths = [];

        //     foreach ($request->file('files_path') as $file) {
        //         $filename =  $data['tracking_no'] . '-' . $file->getClientOriginalName();
        //         $path = $file->storeAs('attachments', $filename, 'private');
        //         $filePaths[] = $path;
        //     }


        //     $data['files_path'] = json_encode($filePaths);
        // } else {
        //     $data['files_path'] = "[]";
        // }


        $data['status'] = 'For review';

        $savedRequest = $service->requests()->create($data);

        $redirectUrl = route('applications.show') . '?search=' . $savedRequest->tracking_no;

        return redirect($redirectUrl)->with('prompt', "Please take note of your tracking code: {tracking_code}. You'll need this code to trace the progress of your request.");
    })->name('applications.post');


    Route::get('applications', [RequestController::class, 'show'])->name('applications.show');

    Route::get('applications/{tracking}', function (string $tracking) {
        $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
        return view('requests.client-edit', ['request' => $request]);
    })->name('applications.edit');

    Route::put('applications/{tracking}', function (RequestRequest $request) {
        $requestToUpdate = RequestModel::where('tracking_no', $request->input('tracking_no'))->firstOrFail();

        // $existingFilesPath = json_decode($requestToUpdate->files_path, true) ?? [];

        // $finalFiles = "[]";
        // if ($request->has('files_path')) {
        //     $filePaths = [];

        //     foreach ($request->file('files_path') as $file) {
        //         $filename =  $requestToUpdate->tracking_no . '-' . $file->getClientOriginalName();
        //         $path = $file->storeAs('attachments', $filename, 'private');
        //         $filePaths[] = $path;
        //     }


        //     $finalFiles = json_encode($filePaths);
        // }

        // $removedFiles = explode(',', $request->input('files_to_remove'));
        // // Delete each file in the removedFiles array from the storage
        // foreach ($removedFiles as $file) {
        //     if (Storage::disk('private')->exists($file)) {
        //         Storage::disk('private')->delete($file);
        //     }
        // }


        // $existingFilesPath = array_diff($existingFilesPath, $removedFiles);

        // $mergedPaths = array_merge($existingFilesPath, json_decode($finalFiles));

        // $request['files_path'] = [];
        $data = $request->validated();

        $data['status'] = 'For review';
        // $data['files_path'] = json_encode($mergedPaths);

        $requestToUpdate->update($data);
        $redirectUrl = route('applications.show') . '?search=' . $requestToUpdate->tracking_no;
        return redirect($redirectUrl);
    })->name('applications.update');
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

            $serviceId = Service::where('name', $service)->first()?->id;

            $requests = RequestModel::where('user_id', Auth::id())->when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
                ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
                ->orderBy($sort ?? 'id', $sort == 'name' ? 'asc' : 'desc')
                ->paginate(15);
            return view('transactions.index', ['requests' => $requests, 'services' => Service::all()]);
        })->name('transactions.index');

        Route::get('transactions/{tracking}/edit', function (String $tracking) {
            $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
            return view('transactions.edit', ['request' => $request]);
        })->name('transactions.edit');

        Route::put('requests-submit', [RequestController::class, 'submit'])->name('applications.submit');

        Route::put('requests-submit-cancel', [RequestController::class, 'cancelSubmit'])->name('applications.submit.cancel');

        Route::put('requests-reject', [RequestController::class, 'reject'])->name('applications.reject');

        Route::put('requests-reject-cancel', [RequestController::class, 'cancelReject'])->name('applications.cancel.reject');

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

            $files = json_decode($request->files_path, true);

            if (empty($files)) {
                abort(404, 'No files found.');
            }

            $zipFileName = "attachments-{$request->tracking_no}.zip";
            $zipPath = storage_path("app/private/attachments/{$zipFileName}");

            $zip = new ZipArchive;

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                foreach ($files as $file) {
                    $filePath = storage_path('app/private/attachments/' . basename($file));
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, basename($file));
                    }
                }
                $zip->close();
            } else {
                abort(500, 'Could not create ZIP file.');
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        })->name('files.downloadZip');
    });

    // For Admin
    Route::middleware('role:Administrator')->group(function () {

        Route::get('submissions', function (Request $request) {
            $searchKey = $request->input('search');
            $service = $request->input('filter');
            $sort = $request->input('sort');

            $serviceId = Service::where('name', $service)->first()?->id;

            $requests = RequestModel::where('status', "For Approval")->when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
                ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
                ->orderBy($sort ?? 'id', $sort == 'name' ? 'asc' : 'desc')
                ->paginate(15);
            return view('admin.index', ['requests' => $requests, 'services' => Service::all()]);
        })->name('admin.index');

        Route::get('submissions/{tracking}', function (String $tracking) {
            $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
            return view('admin.edit', ['request' => $request]);
        })->name('admin.edit');

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
