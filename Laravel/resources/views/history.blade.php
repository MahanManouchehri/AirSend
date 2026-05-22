<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لینک‌های من - AirSend</title>
    <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <style>
        body { background-color: #f4f6fb; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .table-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            margin-top: 3rem;
            padding: 2rem;
        }
        .expired-row { opacity: 0.6; }
        .copy-btn { cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>📂 لینک‌های من</h3>
                <a href="/" class="btn btn-primary btn-sm">+ ارسال جدید</a>
            </div>

            @if($files->isEmpty())
                <div class="alert alert-info text-center">هنوز فایلی آپلود نکرده‌اید.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>نام فایل</th>
                                <th>حجم</th>
                                <th>وضعیت</th>
                                <th>زمان انقضا</th>
                                <th>لینک</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                            <tr class="{{ $file->isExpired() ? 'expired-row' : '' }}">
                                <td>{{ $file->original_name }}</td>
                                <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                                <td>
                                    @if($file->isExpired())
                                        <span class="badge bg-danger">منقضی شده</span>
                                    @else
                                        <span class="badge bg-success">فعال</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($file->expires_at)->format('Y/m/d H:i') }}</td>
                                <td>
                                    @if(!$file->isExpired())
                                        <a href="{{ route('file.show', $file->unique_hash) }}" class="btn btn-sm btn-outline-primary" target="_blank">🔗 دریافت</a>
                                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-link="{{ route('file.show', $file->unique_hash) }}">📋 کپی</button>
                                    @else
                                        <span class="text-muted">__</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        // کپی لینک با کلیک روی دکمه
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const link = this.dataset.link;
                navigator.clipboard.writeText(link).then(() => {
                    alert('لینک کپی شد');
                });
            });
        });
    </script>
</body>
</html>
