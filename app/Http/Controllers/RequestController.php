<?php

namespace App\Http\Controllers;

use App\Mail\RejectMail;
use App\Mail\ScheduleMail;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKey = $request->input('search');
        $service = $request->input('filter');
        $sort = $request->input('sort');

        $serviceId = Service::where('name', $service)->first()?->id;

        $requests = RequestModel::where(function ($query) {
            $query->whereNull('user_id')
                ->orWhere('user_id', 0)
                ->orWhere('user_id', '');
        })
            ->when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
            ->orderBy($sort ?? 'id', $sort == 'lastname' ? 'asc' : 'desc')
            ->paginate(15);
        return view('requests.index', ['requests' => $requests, 'services' => Service::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $search = $request->input('search');

        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $middleinitial = $request->input('middleinitial');

        switch ($request->input('mode')) {
            case 'applicant':
                $query = RequestModel::where('firstname', $firstname)
                    ->where('lastname', $lastname)->whereRaw('LEFT(middlename, 1) = ?', [$middleinitial]);

                $requestData = $query->first();

                return view('requests.show', [
                    'request' => $requestData,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'middleinitial' => $middleinitial
                ]);

            case 'tracking':
                $requestData = RequestModel::where('tracking_no', $search)->first();
                return view('requests.show', [
                    'request' => $requestData,
                    'search' => $search,
                ]);

            default:
                return view('requests.show');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $tracking)
    {
        $request = RequestModel::where('tracking_no', $tracking)->firstOrFail();
        return view('requests.edit', ['request' => $request]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function reject(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $trackedRequest = RequestModel::where('tracking_no', $request->input('tracking'))->firstOrFail();
        $trackedRequest->message = $request->input('message');
        $trackedRequest->status = 'Rejected';
        $trackedRequest->user_id = Auth::id();
        Mail::to($trackedRequest->email)->send(new RejectMail($trackedRequest->tracking_no,  $trackedRequest->message));


        $trackedRequest->save();

        return redirect()->route('requests.edit', $trackedRequest->tracking_no);
    }

    public function cancelReject(Request $request)
    {
        $trackedRequest = RequestModel::where('tracking_no', $request->input('tracking'))->firstOrFail();
        $trackedRequest->message = '';
        $trackedRequest->status = 'For review';
        $trackedRequest->save();
        return redirect()->route('requests.edit', $trackedRequest->tracking_no);
    }

    public function submit(Request $request)
    {
        $trackedRequest = RequestModel::where('tracking_no', $request->input('tracking'))->firstOrFail();
        $trackedRequest->status = 'For schedule';
        $trackedRequest->message = '';
        $trackedRequest->user_id = Auth::id();

        Mail::to($trackedRequest->email)->send(new ScheduleMail($trackedRequest->tracking_no));


        $trackedRequest->save();
        return redirect()->route('requests.edit', $trackedRequest->tracking_no)->with('success', 'Request Submitted for Schedule.');
    }

    public function cancelSubmit(Request $request)
    {
        $trackedRequest = RequestModel::where('tracking_no', $request->input('tracking'))->firstOrFail();
        $trackedRequest->status = 'For review';
        $trackedRequest->message = '';
        $trackedRequest->save();
        return redirect()->route('requests.edit', $trackedRequest->tracking_no);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
