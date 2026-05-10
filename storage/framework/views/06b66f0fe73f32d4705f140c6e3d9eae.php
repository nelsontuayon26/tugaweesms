<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }
        .header p {
            color: rgba(255,255,255,0.85);
            font-size: 14px;
            margin: 8px 0 0;
        }
        .content {
            padding: 32px 24px;
            text-align: center;
        }
        .otp-box {
            background: #f0fdfa;
            border: 2px dashed #14b8a6;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #0d9488;
            font-family: 'Courier New', monospace;
        }
        .note {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
            margin-top: 24px;
        }
        .warning {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 20px;
            color: #9a3412;
            font-size: 12px;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tugawe Elementary School</h1>
            <p>Pupil Management System</p>
        </div>
        <div class="content">
            <p style="color: #334155; font-size: 15px; margin: 0;">Your registration verification code is:</p>
            
            <div class="otp-box">
                <div class="otp-code"><?php echo e($otp); ?></div>
            </div>
            
            <p class="note">
                This code will expire in <strong>10 minutes</strong>.<br>
                Do not share this code with anyone.
            </p>
            
            <div class="warning">
                If you did not request this code, please ignore this email.
            </div>
        </div>
        <div class="footer">
            <p>Tugawe Elementary School • DepEd Negros Oriental</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\emails\otp.blade.php ENDPATH**/ ?>