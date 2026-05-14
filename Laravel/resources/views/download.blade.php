<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>دانلود فایل - AirSend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .download-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            margin-top: 5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 text-center">
                <div class="download-card">
                    <h3>📥 فایل شما آماده است</h3>
                    <p class="mt-3">نام فایل: <strong>{{ $file->original_name }}</strong></p>
                    <p>حجم: {{ number_format($file->size / 1024, 2) }} کیلوبایت</p>
                    <p>زمان انقضا: {{ \Carbon\Carbon::parse($file->expires_at)->format('Y/m/d H:i') }}</p>
                    <a href="{{ route('file.download', $file->unique_hash) }}" class="btn btn-primary btn-lg mt-3">
                        ⬇️ دانلود فایل
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
