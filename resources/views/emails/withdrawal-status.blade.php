<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Withdrawal Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .header.approved { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .header.completed { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .header.cancelled, .header.rejected { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .info-box { background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #666; }
        .value { font-weight: bold; }
        .button { display: inline-block; background: #667eea; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        .reason-box { background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header {{ $status }}">
        <h1>
            @if($status === 'approved')
                ✅ Withdrawal Approved
            @elseif($status === 'completed')
                💰 Withdrawal Completed
            @elseif($status === 'cancelled')
                ❌ Withdrawal Cancelled
            @elseif($status === 'rejected')
                ❌ Withdrawal Rejected
            @endif
        </h1>
    </div>
    <div class="content">
        <p>Hi <strong>{{ $withdrawal->user->name ?? 'User' }}</strong>,</p>
        
        @if($status === 'approved')
            <p>Great news! Your withdrawal request has been <strong>approved</strong> and is being processed.</p>
        @elseif($status === 'completed')
            <p>Your withdrawal has been <strong>completed</strong>! The funds have been sent to your payment method.</p>
        @elseif($status === 'cancelled')
            <p>Your withdrawal request has been <strong>cancelled</strong>.</p>
        @elseif($status === 'rejected')
            <p>Unfortunately, your withdrawal request has been <strong>rejected</strong>.</p>
        @endif
        
        <div class="info-box">
            <div class="info-row">
                <span class="label">Amount:</span>
                <span class="value">${{ number_format($withdrawal->amount, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Payment Method:</span>
                <span class="value">{{ ucfirst($withdrawal->payment_method ?? 'N/A') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Request Date:</span>
                <span class="value">{{ $withdrawal->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">{{ ucfirst($status) }}</span>
            </div>
        </div>
        
        @if($reason)
            <div class="reason-box">
                <strong>Reason:</strong> {{ $reason }}
            </div>
        @endif
        
        <a href="{{ url('/user/withdrawals') }}" class="button">View Withdrawals</a>
        
        <p style="margin-top: 30px;">If you have any questions, please contact our support team.</p>
        
        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
