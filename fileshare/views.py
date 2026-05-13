from django.shortcuts import render, get_object_or_404, redirect
from django.http import FileResponse, Http404, JsonResponse
from django.views.decorators.http import require_http_methods
from django.views.decorators.csrf import csrf_exempt  # فقط برای آپلود AJAX (توضیح می‌دم)
from django.utils import timezone
from .models import FileShare
import json

def home(request):
    """صفحه اصلی با Dropzone."""
    return render(request, 'fileshare/home.html')

@csrf_exempt  # Dropzone نیاز به ارسال CSRF دارد که در هدر می‌فرستیم، اما اینجا برای سادگی فعلاً استثنا می‌کنم (بهتره با هدر CSRF انجام بشه)
@require_http_methods(["POST"])
def upload_file(request):
    """دریافت فایل از Dropzone و برگرداندن لینک دانلود."""
    file = request.FILES.get('file')
    if not file:
        return JsonResponse({'error': 'هیچ فایلی ارسال نشده'}, status=400)

    # محدودیت حجم (مثلاً ۵۰ مگابایت)
    if file.size > 50 * 1024 * 1024:
        return JsonResponse({'error': 'حداکثر حجم فایل ۵۰ مگابایت است'}, status=400)

    # ذخیره در دیتابیس
    fs = FileShare.objects.create(
        file=file,
        original_name=file.name,
    )

    # برگرداندن لینک دانلود
    download_url = request.build_absolute_uri(f'/d/{fs.unique_hash}/')
    return JsonResponse({
        'success': True,
        'download_url': download_url,
        'expires_at': fs.expires_at.strftime('%Y-%m-%d %H:%M'),
    })

def download_file(request, hash_value):
    """بررسی انقضا و سرو فایل برای دانلود."""
    fs = get_object_or_404(FileShare, unique_hash=hash_value)
    if fs.is_expired():
        # می‌توانی صفحه خطای زیبا برگردانی یا پیام ساده
        return render(request, 'fileshare/expired.html', status=410)
    
    # سرو فایل با FileResponse
    response = FileResponse(fs.file.open('rb'), as_attachment=True, filename=fs.original_name)
    return response