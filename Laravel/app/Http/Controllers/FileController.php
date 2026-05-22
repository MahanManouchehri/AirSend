<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\File;

class FileController extends Controller
{
    /**
     * صفحه آپلود + تاریخچه
     */
    public function create(Request $request)
    {
        $userToken = $request->cookie('uploader_token');
        $files = File::where('user_token', $userToken)
                     ->orderByDesc('created_at')
                     ->get();

        return view('upload', compact('files'));
    }

    /**
     * دریافت و ذخیره فایل
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // 20MB
        ]);

        $uploadedFile = $request->file('file');

        $storedName = time() . '_' . Str::random(10) . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->storeAs('files', $storedName, 'local');

        $hash = Str::random(16);
        while (File::where('unique_hash', $hash)->exists()) {
            $hash = Str::random(16);
        }

        $userToken = $request->cookie('uploader_token');

        $file = File::create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name'   => $storedName,
            'unique_hash'   => $hash,
            'size'          => $uploadedFile->getSize(),
            'expires_at'    => now()->addDays(3),
            'user_token'    => $userToken,
        ]);

        return response()->json([
            'success'       => true,
            'message'       => 'فایل با موفقیت آپلود شد.',
            'download_url'  => route('file.show', $hash),
            'expires_at'    => $file->expires_at->toDateTimeString(),
            'file' => [
                'original_name'        => $file->original_name,
                'size'                 => $file->size,
                'hash'                 => $file->unique_hash,
                'expires_at_formatted' => $file->expires_at->format('Y/m/d H:i'),
            ],
        ]);
    }

    /**
     * نمایش لینک دانلود
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
     * خروجی فایل برای دانلود
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
