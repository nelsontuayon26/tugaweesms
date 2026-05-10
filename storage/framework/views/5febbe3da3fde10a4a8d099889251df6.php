<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - TESSMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .icon {
            width: 80px;
            height: 80px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        
        .icon svg {
            width: 40px;
            height: 40px;
            color: #f59e0b;
        }
        
        h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 12px;
        }
        
        p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .features {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
        }
        
        .features h3 {
            font-size: 14px;
            color: #374151;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .feature-list {
            list-style: none;
        }
        
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .feature-list li::before {
            content: "✓";
            color: #10b981;
            font-weight: bold;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        .btn:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: transparent;
            color: #6b7280;
            margin-top: 12px;
        }
        
        .btn-secondary:hover {
            background: #f3f4f6;
        }
        
        .status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .status.online {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status.offline {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        .status.online .status-dot {
            background: #10b981;
        }
        
        .status.offline .status-dot {
            background: #ef4444;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"/>
            </svg>
        </div>
        
        <h1>You're Offline</h1>
        <p>Don't worry! You can still access previously loaded pages and some features work offline.</p>
        
        <div class="features">
            <h3>Available Offline</h3>
            <ul class="feature-list">
                <li>View cached dashboard</li>
                <li>Check your grades</li>
                <li>View attendance history</li>
                <li>Read announcements</li>
                <li>Take attendance (will sync later)</li>
            </ul>
        </div>
        
        <button class="btn" onclick="window.location.reload()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Try Again
        </button>
        
        <a href="/dashboard" class="btn btn-secondary">Go to Dashboard</a>
        
        <div id="connection-status" class="status offline">
            <span class="status-dot"></span>
            <span id="status-text">Waiting for connection...</span>
        </div>
    </div>
    
    <script>
        function updateStatus() {
            const statusEl = document.getElementById('connection-status');
            const statusText = document.getElementById('status-text');
            
            if (navigator.onLine) {
                statusEl.classList.remove('offline');
                statusEl.classList.add('online');
                statusText.textContent = 'Connection restored!';
                
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);
            } else {
                statusEl.classList.remove('online');
                statusEl.classList.add('offline');
                statusText.textContent = 'Waiting for connection...';
            }
        }
        
        window.addEventListener('online', updateStatus);
        window.addEventListener('offline', updateStatus);
        updateStatus();
    </script>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\offline.blade.php ENDPATH**/ ?>