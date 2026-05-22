<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>دریافت فایل – AirSend</title>
    <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --surface: #ffffff;
            --text: #1e293b;
        }
        body {
            background: linear-gradient(135deg, #eef2ff 0%, #f1f5f9 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1rem;
        }
        .download-card {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);
            padding: 2.5rem;
            text-align: center;
            max-width: 450px;
            width: 100%;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        h2 {
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1.5rem;
        }
        .file-info {
            background: rgba(0,0,0,0.03);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            color: #334155;
        }
        .btn-download {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border: none;
            color: white;
            padding: 0.9rem 2.5rem;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(99,102,241,0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="download-card">
        <div class="icon">📥</div>
        <h2>فایل شما آماده دریافت است</h2>
        <div class="file-info">
            <p class="mb-1"><strong>{{ $file->original_name }}</strong></p>
            <p class="mb-1">حجم: {{ number_format($file->size / 1024, 2) }} کیلوبایت</p>
            <p class="mb-0">⏳ منقضی می‌شود: {{ \Carbon\Carbon::parse($file->expires_at)->format('Y/m/d H:i') }}</p>
        </div>
        <a href="{{ route('file.download', $file->unique_hash) }}" class="btn-download">
            دانلود فایل
        </a>
    </div>
</body>
</html>
