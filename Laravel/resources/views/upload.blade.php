<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirSend – ارسال فایل</title>
    <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #10b981;
            --surface: #ffffff;
            --bg: #f1f5f9;
            --text: #1e293b;
            --text-light: #64748b;
            --shadow: 0 25px 50px -12px rgba(0,0,0,0.08);
            --radius: 24px;
        }

        * { box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #eef2ff 0%, #f1f5f9 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            margin: 0;
        }

        .main-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Card container */
        .glass-card {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 3rem 2rem;
            transition: all 0.3s ease;
        }

        .app-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
        }

        /* Dropzone styling */
        .dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            background: rgba(255,255,255,0.6);
            transition: all 0.3s ease;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropzone:hover {
            border-color: var(--primary);
            background: rgba(99,102,241,0.04);
            transform: translateY(-2px);
        }

        .dropzone .dz-message {
            font-size: 1.2rem;
            color: var(--text-light);
            margin: 0;
        }

        .dropzone .dz-message::before {
            content: "⬆️";
            display: block;
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        /* Progress bar override */
        .dz-progress {
            display: block !important;
            height: 8px !important;
            background: #e2e8f0;
            border-radius: 10px;
            margin-top: 12px;
        }

        .dz-upload {
            background: linear-gradient(90deg, var(--primary), #8b5cf6) !important;
            border-radius: 10px;
            height: 100%;
            width: 0;
        }

        /* Success message */
        .upload-success-alert {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 2rem;
            display: none;
        }

        .upload-success-alert a {
            color: var(--primary-dark);
            font-weight: 600;
            text-decoration: none;
            word-break: break-all;
        }

        /* History section */
        .history-section {
            margin-top: 3rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table {
            background: transparent;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead th {
            border-bottom: none;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.5rem 0.75rem;
        }

        .table tbody tr {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .table tbody tr:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }

        .table td {
            vertical-align: middle;
            padding: 0.9rem 0.75rem;
            border: none;
            color: var(--text);
        }

        .table td:first-child {
            border-radius: 0 12px 12px 0;
        }

        .table td:last-child {
            border-radius: 12px 0 0 12px;
        }

        .badge-active {
            background: rgba(16,185,129,0.15);
            color: #065f46;
            padding: 0.3em 0.8em;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-expired {
            background: rgba(239,68,68,0.1);
            color: #991b1b;
            padding: 0.3em 0.8em;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .btn-glass {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 10px;
            padding: 0.35rem 0.8rem;
            color: var(--text);
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-glass:hover {
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .btn-copy {
            cursor: pointer;
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            background: rgba(255,255,255,0.5);
            border-radius: 16px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .glass-card {
                padding: 2rem 1rem;
            }
            .app-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="glass-card">
        <!-- Header -->
        <div class="text-center">
            <h1 class="app-title">AirSend</h1>
            <p class="subtitle">ارسال فایل، ساده، سریع و ایمن</p>
        </div>

        <!-- Upload area -->
        <form action="{{ route('upload.store') }}" method="POST"
              class="dropzone" id="fileDropzone">
            @csrf
        </form>

        <!-- Temporary success message (optional) -->
        <div class="upload-success-alert" id="uploadSuccess">
            ✅ فایل بارگذاری شد. لینک: <a href="#" id="tempDownloadLink" target="_blank"></a>
            <br><small class="text-muted">معتبر تا <span id="tempExpireTime"></span></small>
        </div>

        <!-- History of uploads -->
        <div class="history-section">
            <div class="d-flex justify-content-between align-items-center">
                <div class="section-title">📋 فایل‌های اخیر شما</div>
            </div>

            <div id="historyContainer">
                @if(isset($files) && $files->count())
                    <div class="table-responsive">
                        <table class="table" id="historyTable">
                            <thead>
                                <tr>
                                    <th>نام فایل</th>
                                    <th>حجم</th>
                                    <th>وضعیت</th>
                                    <th>انقضا</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                <tr data-hash="{{ $file->unique_hash }}">
                                    <td class="fw-medium">{{ $file->original_name }}</td>
                                    <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                                    <td>
                                        @if($file->isExpired())
                                            <span class="badge-expired">منقضی</span>
                                        @else
                                            <span class="badge-active">فعال</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($file->expires_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        @if(!$file->isExpired())
                                            <a href="{{ route('file.show', $file->unique_hash) }}" class="btn-glass" target="_blank">دریافت</a>
                                            <button class="btn-copy ms-1" data-link="{{ route('file.show', $file->unique_hash) }}">کپی</button>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state" id="emptyState">
                        <span style="font-size:2rem;">📭</span>
                        <p class="mt-2">هنوز فایلی آپلود نکرده‌اید</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/dropzone-min.js') }}"></script>
<script>
    Dropzone.autoDiscover = false;

    $(function() {
        var myDropzone = new Dropzone("#fileDropzone", {
            paramName: "file",
            maxFilesize: 20,
            dictDefaultMessage: "فایل را اینجا بکشید یا کلیک کنید",
            dictFallbackMessage: "مرورگر شما پشتیبانی نمی‌کند",
            dictFileTooBig: "حجم فایل بیش از ۲۰ مگابایت است",
            init: function() {
                this.on("success", function(file, response) {
                    if (response.success) {
                        // Show temporary success
                        $('#tempDownloadLink').attr('href', response.download_url).text(response.download_url);
                        $('#tempExpireTime').text(response.file.expires_at_formatted);
                        $('#uploadSuccess').show();

                        // Add row to history table (and remove empty state)
                        addFileRow(response.file);
                        this.removeFile(file);
                    }
                });
                this.on("error", function(file, msg) {
                    alert("خطا: " + msg);
                });
            }
        });

        function addFileRow(f) {
            $('#emptyState').remove();
            if ($('#historyTable').length === 0) {
                var tableHtml = '<div class="table-responsive"><table class="table" id="historyTable"><thead><tr><th>نام فایل</th><th>حجم</th><th>وضعیت</th><th>انقضا</th><th>عملیات</th></tr></thead><tbody></tbody></table></div>';
                $('#historyContainer').html(tableHtml);
            }

            var sizeKB = (f.size / 1024).toFixed(2);
            var row = `<tr data-hash="${f.hash}">
                <td class="fw-medium">${escapeHtml(f.original_name)}</td>
                <td>${sizeKB} KB</td>
                <td><span class="badge-active">فعال</span></td>
                <td>${f.expires_at_formatted}</td>
                <td>
                    <a href="/d/${f.hash}" class="btn-glass" target="_blank">دریافت</a>
                    <button class="btn-copy ms-1" data-link="/d/${f.hash}">کپی</button>
                </td>
            </tr>`;
            $('#historyTable tbody').prepend(row);
        }

        // Copy to clipboard
        $(document).on('click', '.btn-copy', function() {
            var link = $(this).data('link');
            var fullLink = window.location.origin + link;
            navigator.clipboard.writeText(fullLink).then(function() {
                // subtle feedback
                var btn = $(this);
                var orig = btn.text();
                btn.text('✓ کپی شد');
                setTimeout(() => btn.text(orig), 1500);
            }.bind(this)).catch(function() {
                // fallback
                var temp = $('<input>');
                $('body').append(temp);
                temp.val(fullLink).select();
                document.execCommand('copy');
                temp.remove();
                var btn = $(this);
                var orig = btn.text();
                btn.text('✓ کپی شد');
                setTimeout(() => btn.text(orig), 1500);
            }.bind(this));
        });

        function escapeHtml(text) {
            return $('<span>').text(text).html();
        }
    });
</script>
</body>
</html>
