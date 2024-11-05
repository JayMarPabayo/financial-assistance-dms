<div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
    <h1 style="color: #5cb85c; text-align: center;">Request Approved</h1>

    <p>Hello,</p>
    <p>We are pleased to inform you that your request with tracking number <strong>{{ $trackingNumber }}</strong> for <strong>{{ $requestType }}</strong> has been approved.</p>

    <p style="font-size: 14px; color: #666;">
        You are scheduled to meet on <strong>{{ $schedule->date }}</strong> at <strong>{{ $schedule->time }}</strong>. Please arrive on time and bring any necessary documents or materials required for your request.
    </p>

    @if(!empty($schedule->notes))
        <p style="font-size: 14px; color: #666;">
            <strong>Additional Notes:</strong> {{ $schedule->notes }}
        </p>
    @endif

    <p>Thank you,<br>
</div>
