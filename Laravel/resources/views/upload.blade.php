<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirSend - ارسال فایل</title>
    <!-- بوت‌استرپ ۵ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
    <style>
        body {
            background-color: #f4f6fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .upload-container {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            margin-top: 5rem;
        }
        .dropzone {
            border: 2px dashed #adb5bd;
            border-radius: 12px;
            background: #f8f9fa;
            transition: 0.3s;
        }
        .dropzone:hover {
            border-color: #0d6efd;
            background: #f0f4ff;
        }
        .dz-message {
            font-size: 1.2rem;
            color: #6c757d;
        }
        #uploadResult {
            display: none;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="upload-container text-center">
                    <h2 class="mb-2">📁 AirSend</h2>
                    <p class="text-muted mb-4">فایل خود را بکشید و رها کنید یا کلیک کنید</p>

                    <form action="{{ route('upload.store') }}" method="POST"
                          class="dropzone" id="fileDropzone">
                        @csrf
                    </form>

                    <div id="uploadResult" class="alert alert-success">
                        <p class="mb-1">✅ بارگذاری موفق</p>
                        <a id="downloadLink" href="#" class="fw-bold" target="_blank"></a>
                        <small class="d-block text-muted">این لینک تا <span id="expireTime"></span> معتبر است</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (برای راحتی) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Dropzone JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        $(function() {
            let myDropzone = new Dropzone("#fileDropzone", {
                paramName: "file",
                maxFilesize: 20, // MB
                acceptedFiles: "*",
                dictDefaultMessage: "فایل را اینجا رها کنید یا کلیک کنید",
                dictFallbackMessage: "مرورگر شما از این قابلیت پشتیبانی نمی‌کند.",
                dictFileTooBig: "حجم فایل بیش از 20 مگابایت است.",
                init: function() {
                    this.on("success", function(file, response) {
                        if (response.success) {
                            $('#downloadLink')
                                .attr('href', response.download_url)
                                .text(response.download_url);
                            $('#expireTime').text(response.expires_at);
                            $('#uploadResult').show();
                            this.removeFile(file); // برای آپلود مجدد
                        }
                    });
                    this.on("error", function(file, errorMessage) {
                        alert("خطا: " + errorMessage);
                    });
                }
            });
        });
    </script>
</body>
</html>
