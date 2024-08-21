<?php

use App\Http\Controllers\ServiceController;
use App\Http\Requests\RequestRequest;
use App\Models\Request as RequestModel;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            return redirect()->back()->with('error', 'Invalid credentials');
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

        $redirectUrl = route('requests.show') . '?search=' . $savedRequest->tracking_no;

        return redirect($redirectUrl);
    })->name('requests.post');

    Route::get('applications', function (Request $request) {
        // $tracking = '66b71cc58a072';
        $search = $request->input('search');
        $request = RequestModel::where('tracking_no', $search)->first();
        return view('requests.show', ['request' => $request]);
    })->name('requests.show');
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

    Route::get('requests', function (Request $request) {
        $searchKey = $request->input('search');
        $service = $request->input('filter');
        $sort = $request->input('sort');

        $serviceId = Service::where('name', $service)->first()?->id;

        $requests = RequestModel::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
            ->orderBy($sort ?? 'id', $sort == 'name' ? 'asc' : 'desc')
            ->paginate(15);
        return view('requests.index', ['requests' => $requests, 'services' => Service::all()]);
    })->name('requests.index');

    Route::get('requests/{tracking}', function ($tracking) {
        $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
        return view('requests.update', ['request' => $request]);
    })->name('requests.update');


    Route::get('download/{filename}', function ($filename) {
        $filePath = storage_path("app/private/attachments/{$filename}");

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            abort(404, 'File not found.');
        }
    })->name('file.download');
});
