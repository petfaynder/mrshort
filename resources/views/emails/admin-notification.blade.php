<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .info-box { background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #666; font-size: 12px; text-transform: uppercase; }
        .value { font-weight: bold; margin-top: 4px; }
        .button { display: inline-block; background: #667eea; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            @if($type === 'new_user')
                👤 New User Registration
            @elseif($type === 'new_withdrawal')
                💳 New Withdrawal Request
            @else
                🔔 Admin Notification
            @endif
        </h1>
    </div>
    <div class="content">
        @if($type === 'new_user')
            <p>A new user has registered on {{ config('app.name') }}.</p>
            
            <div class="info-box">
                <div class="info-row">
                    <div class="label">Name</div>
                    <div class="value">{{ $data['name'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Email</div>
                    <div class="value">{{ $data['email'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Registered At</div>
                    <div class="value">{{ $data['registered_at'] ?? now()->format('M d, Y H:i') }}</div>
                </div>
            </div>
            
            <a href="{{ url('/admin/users') }}" class="button">View Users</a>
            
        @elseif($type === 'new_withdrawal')
            <p>A new withdrawal request has been submitted.</p>
            
            <div class="info-box">
                <div class="info-row">
                    <div class="label">User</div>
                    <div class="value">{{ $data['user_name'] ?? 'N/A' }} ({{ $data['user_email'] ?? 'N/A' }})</div>
                </div>
                <div class="info-row">
                    <div class="label">Amount</div>
                    <div class="value">${{ number_format($data['amount'] ?? 0, 2) }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Payment Method</div>
                    <div class="value">{{ ucfirst($data['payment_method'] ?? 'N/A') }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Requested At</div>
                    <div class="value">{{ $data['requested_at'] ?? now()->format('M d, Y H:i') }}</div>
                </div>
            </div>
            
            <a href="{{ url('/admin/withdraw-requests') }}" class="button">Review Requests</a>
        @endif
        
        <p style="margin-top: 30px; color: #666; font-size: 12px;">This is an automated admin notification from {{ config('app.name') }}.</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
