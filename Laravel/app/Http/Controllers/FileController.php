<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * نمایش صفحه آپلود
     */
    public function create()
    {
        return view('upload');
    }

    /**
     * پردازش آپلود فایل
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // حداکثر 20 مگابایت
        ]);

        $uploadedFile = $request->file('file');

        // ساخت نام یکتا برای ذخیره در سرور
        $storedName = time() . '_' . Str::random(10) . '.' . $uploadedFile->getClientOriginalExtension();

        // ذخیره فایل در دیسک local (پوشه storage/app/private/files)
        $path = $uploadedFile->storeAs('files', $storedName, 'local');

        // تولید هش یکتا برای لینک اشتراک‌گذاری
        $hash = Str::random(16);
        while (File::where('unique_hash', $hash)->exists()) {
            $hash = Str::random(16);
        }

        // ذخیره اطلاعات در دیتابیس
        $file = File::create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name'   => $storedName,
            'unique_hash'   => $hash,
            'size'          => $uploadedFile->getSize(),
            'expires_at'    => now()->addHours(24), // ۲۴ ساعت اعتبار
        ]);

        // پاسخ JSON برای Dropzone
        return response()->json([
            'success'      => true,
            'message'      => 'فایل با موفقیت آپلود شد.',
            'download_url' => route('file.show', $hash),
            'expires_at'   => $file->expires_at->toDateTimeString(),
        ]);
    }

    /**
     * نمایش صفحه دانلود
     */
    public function show($hash)
    {
        $file = File::where('unique_hash', $hash)->firstOrFail();

        if ($file->isExpired()) {
            return view('expired');
        }

        return view('download', compact('file'));
    }

    /**
     * ارسال فایل برای دانلود
     */
    public function download($hash)
    {
        $file = File::where('unique_hash', $hash)->firstOrFail();

        if ($file->isExpired()) {
            abort(410, 'لینک منقضی شده است.');
        }

        $path = storage_path('app/private/files/' . $file->stored_name);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $file->original_name);
    }
}
