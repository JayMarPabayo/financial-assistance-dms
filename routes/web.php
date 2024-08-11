<?php

use App\Http\Controllers\ServiceController;
use App\Http\Requests\RequestRequest;
use App\Models\Request as RequestModel;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('home', function () {
    return view('index');
})->name('home');


Route::resource('services', ServiceController::class);

Route::post('services/{service:slug}', function (RequestRequest $request, $slug) {
    $service = Service::where('slug', $slug)->firstOrFail();

    $data = $request->validated();

    if ($request->has('files_path')) {
        $filePaths = [];
        if ($request->has('files_path')) {
            $filePaths = [];

            foreach ($request->file('files_path') as $file) {
                $path = $file->store('attachments', 'private');
                $filePaths[] = $path;
            }

            $data['files_path'] = json_encode($filePaths);
        }
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

Route::get('login', function () {
    return view('auth.index');
})->name('auth.index');
