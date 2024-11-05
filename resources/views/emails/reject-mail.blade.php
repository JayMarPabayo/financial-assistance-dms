<div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
    <h1 style="color: #d9534f; text-align: center;">Request Rejected</h1>

    <p>Hello,</p>
    <p>Your request with tracking number <strong>{{ $trackingNumber }}</strong> for <strong>{{ $requestType }}</strong> has been rejected.</p>

    <p style="font-size: 14px; color: #666;">
        Please check the details and make any necessary updates by clicking the link below:
    </p>

    <p style="text-align: center;">
        <a href="http://127.0.0.1:8000/applications?search={{ $trackingNumber }}"
           style="background-color: #d9534f; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
           Edit Your Request
        </a>
    </p>

    <p style="margin-top: 20px; font-size: 12px; color: #aaa;">
        If you have any questions, please contact us.
    </p>
</div>
