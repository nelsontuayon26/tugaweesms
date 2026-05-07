<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Expired | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 50%, #faf5ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .card {
            background: white;
            padding: 48px;
            border-radius: 24px;
            box-shadow: 0 20px 60px -20px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 420px;
            width: 90%;
        }
        .icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            color: #92400e;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        p {
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1>Password Expired</h1>
        <p>Your password has expired for security reasons. Please contact your system administrator to reset your password, or use the forgot-password link below.</p>
        <a href="{{ route('password.request') }}" class="btn">
            <i class="fas fa-key"></i>
            Reset Password
        </a>
    </div>
</body>
</html>
