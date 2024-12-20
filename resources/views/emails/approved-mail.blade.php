<div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
    <h1 style="color: #5cb85c; text-align: center;">Request Approved</h1>

    <p>Good day! This is fromCongressional Office (Balay Nenotchka/Payag). Your application with the tracking number <strong>{{ $trackingNumber }}</strong> has been approved.</p>

    <p style="font-size: 14px; color: #666;">
        Your schedule is on <strong>{{ $schedule->date }}</strong> at <strong>{{ $schedule->time }}</strong>. Please bring all the original documents:
        <h3 class="mt-1">Requirements</h3>
        @if($service->requirements && $service->requirements->isNotEmpty())
            @foreach ($service->requirements as $index => $requirement)
                <p>{{ ++$index }}. {{ $requirement->name }}</p>
            @endforeach
        @else
        <div>No requirements specified</div>
        @endif
    </p>

    <p style="font-size: 14px; color: #666;">Thank You & Mabuhay!</p>

    @if(!empty($schedule->notes))
        <p style="font-size: 14px; color: #666;">
            <strong>Additional Notes:</strong> {{ $schedule->notes }}
        </p>
    @endif
</div>
