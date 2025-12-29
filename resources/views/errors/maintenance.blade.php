<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - {{ $siteName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }
        
        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, #e94560, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .message {
            font-size: 1.2rem;
            color: #a0a0a0;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .site-name {
            font-size: 0.9rem;
            color: #666;
            margin-top: 3rem;
        }
        
        .progress-bar {
            width: 200px;
            height: 4px;
            background: #333;
            border-radius: 2px;
            margin: 2rem auto;
            overflow: hidden;
        }
        
        .progress-bar::after {
            content: '';
            display: block;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, #e94560, #ff6b6b);
            animation: loading 1.5s infinite ease-in-out;
        }
        
        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(300%); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔧</div>
        <h1>Under Maintenance</h1>
        <p class="message">{{ $message }}</p>
        <div class="progress-bar"></div>
        <p class="site-name">{{ $siteName }}</p>
    </div>
</body>
</html>
