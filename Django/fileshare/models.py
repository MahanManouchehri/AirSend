import uuid
from datetime import timedelta
from django.db import models
from django.utils import timezone

def default_expiry():
    return timezone.now() + timedelta(hours=24)

class FileShare(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    file = models.FileField(upload_to='uploads/%Y/%m/%d/')
    original_name = models.CharField(max_length=255)
    unique_hash = models.CharField(max_length=20, unique=True, blank=True)
    expires_at = models.DateTimeField(default=default_expiry)
    uploaded_at = models.DateTimeField(auto_now_add=True)

    def save(self, *args, **kwargs):
        if not self.unique_hash:
            self.unique_hash = uuid.uuid4().hex[:10]  # ۱۰ کاراکتر هگز
        super().save(*args, **kwargs)

    def is_expired(self):
        return timezone.now() > self.expires_at

    def __str__(self):
        return f"{self.original_name} (expires: {self.expires_at})"
