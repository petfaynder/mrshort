<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { color: #fff; margin: 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; background: #667eea; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{ config('app.name') }}! 🎉</h1>
    </div>
    <div class="content">
        <p>Hi <strong>{{ $user->name }}</strong>,</p>
        
        <p>Thank you for joining {{ config('app.name') }}! We're excited to have you on board.</p>
        
        <p>With your new account, you can:</p>
        <ul>
            <li>✨ Shorten your links and earn money</li>
            <li>📊 Track detailed analytics and statistics</li>
            <li>💰 Withdraw your earnings easily</li>
            <li>🎮 Participate in gamification features</li>
        </ul>
        
        <p>Ready to get started?</p>
        
        <a href="{{ url('/dashboard') }}" class="button">Go to Dashboard</a>
        
        <p style="margin-top: 30px;">If you have any questions, feel free to contact our support team.</p>
        
        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
