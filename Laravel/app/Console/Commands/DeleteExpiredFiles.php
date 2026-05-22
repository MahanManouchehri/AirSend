<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use App\Models\File;

class DeleteExpiredFiles extends Command
{
    protected $signature = 'files:delete-expired';
    protected $description = 'حذف فایل‌های منقضی شده از دیسک و دیتابیس';

    public function handle()
    {
        $expiredFiles = File::where('expires_at', '<', now())->get();

        $count = 0;
        foreach ($expiredFiles as $file) {
            // حذف فایل از storage/app/private/files/
            Storage::disk('local')->delete('private/files/' . $file->stored_name);
            // حذف از دیتابیس
            $file->delete();
            $count++;
        }

        $this->info("{$count} فایل منقضی شده پاکسازی شد.");
    }
}
