from django.core.management.base import BaseCommand
from django.utils import timezone
import os
from fileshare.models import FileShare

class Command(BaseCommand):
    help = 'حذف فایل‌های منقضی شده از دیتابیس و دیسک'

    def handle(self, *args, **options):
        expired_files = FileShare.objects.filter(expires_at__lt=timezone.now())
        count = expired_files.count()
        for fs in expired_files:
            # حذف فایل از دیسک
            if fs.file and os.path.isfile(fs.file.path):
                os.remove(fs.file.path)
            fs.delete()
        self.stdout.write(self.style.SUCCESS(f'{count} فایل منقضی پاک شد.'))
