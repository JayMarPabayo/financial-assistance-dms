<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RequestController;
use App\Http\Requests\RequestRequest;
use App\Models\Request as RequestModel;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as AuthRequest;

Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('home', function () {
        return view('index');
    })->name('home');

    Route::get('login', fn() => to_route('auth.login'))->name('login');

    Route::get('auth/login', function () {
        return view('auth.index');
    })->name('auth.login');

    Route::post('auth/login', function (Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['username', 'password']);
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended('/');
        } else {
            return redirect()->back()->withErrors([
                'credentials' => 'Invalid Credentials'
            ]);
        }
    })->name('auth.login');

    Route::resource('services', ServiceController::class);

    Route::post('services/{service:slug}', function (RequestRequest $request, $slug) {
        $service = Service::where('slug', $slug)->firstOrFail();
        $data = $request->validated();

        if ($request->has('files_path')) {
            $filePaths = [];

            foreach ($request->file('files_path') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'private');
                $filePaths[] = $path;
            }


            $data['files_path'] = json_encode($filePaths);
        }

        $data['status'] = 'For review';

        $savedRequest = $service->requests()->create($data);

        $redirectUrl = route('applications.show') . '?search=' . $savedRequest->tracking_no;

        return redirect($redirectUrl);
    })->name('applications.post');


    Route::get('applications', [RequestController::class, 'show'])->name('applications.show');
});

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect()->route('requests.index');
    });

    Route::delete('auth/logout', function () {
        Auth::logout();
        AuthRequest::session()->invalidate();
        AuthRequest::session()->regenerateToken();
        return redirect('/');
    })->name('auth.logout');

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
