<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

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

        $requests = RequestModel::where('user_id', null)->when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
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
        // $tracking = '66b71cc58a072';
        $search = $request->input('search');
        $request = RequestModel::where('tracking_no', $search)->first();
        return view('requests.show', ['request' => $request]);
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

        $ch = curl_init();
        $parameters = array(
            'apikey' => '8b9effaea9ac4fdd71f0ddccfa7afba4', //Your API KEY
            'number' => '639152796976',
            'message' => 'I just sent my first message with Semaphore',
            'sendername' => 'SEMAPHORE'
        );
        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);

        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

        // Receive response from server
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        dd($output);
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
        $trackedRequest->save();
        return redirect()->route('requests.edit', $trackedRequest->tracking_no);
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
