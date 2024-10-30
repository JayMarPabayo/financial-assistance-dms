<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;

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
        $trackedRequest->user_id = Auth::id();
        $trackedRequest->save();


        $configuration = new Configuration(
            host: '515lmy.api.infobip.com',
            apiKey: 'b717976118ea6c14c74e0681ecdccab3-dc4305ec-22fa-4820-8cfb-6c1cdf80b402'
        );

        $sendSmsApi = new SmsApi(config: $configuration);

        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: '639152796976')
            ],
            from: 'Tagoloan FDMS',
            text: 'This is a dummy SMS message sent using infobip-api-php-client'
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);
        } catch (ApiException $apiException) {
            return redirect()->with('success', $apiException->getMessage());
        }


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
