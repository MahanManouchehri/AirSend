<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لینک منقضی شده – AirSend</title>
    <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --danger: #ef4444;
        }
        body {
            background: linear-gradient(135deg, #fef2f2 0%, #f1f5f9 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1rem;
        }
        .expired-card {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);
            padding: 2.5rem;
            text-align: center;
            max-width: 430px;
            width: 100%;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        h2 {
            color: #991b1b;
            font-weight: 700;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99,102,241,0.35);
            color: white;
        }
    </style>
</head>
<body>
    <div class="expired-card">
        <div class="icon">⏰</div>
        <h2>لینک منقضی شده است</h2>
        <p class="text-muted mt-3">متأسفانه مدت اعتبار این لینک به پایان رسیده و فایل دیگر قابل دریافت نیست.</p>
        <a href="/" class="btn-primary">ارسال فایل جدید</a>
    </div>
</body>
</html>
