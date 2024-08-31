<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\Service;

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

        $requests = RequestModel::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->when($serviceId, fn($query, $serviceId) => $query->where('service_id', $serviceId))
            ->orderBy($sort ?? 'id', $sort == 'name' ? 'asc' : 'desc')
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
        $trackedRequest->status = 'For approval';
        $trackedRequest->message = '';
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
