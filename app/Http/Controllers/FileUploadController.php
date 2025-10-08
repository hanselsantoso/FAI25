<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    private string $storageDirectory = 'uploads/storage-demo';
    private string $publicDirectory = 'uploads/manual-demo';

    public function index()
    {
        $storageFiles = Storage::exists($this->storageDirectory)
            ? collect(Storage::files($this->storageDirectory))->map(function ($path) {
                $name = basename($path);
                //Multi–Purpose Internet Mail Extensions (cek file type)
                $mime = Storage::mimeType($path) ?? 'application/octet-stream';
                $size = Storage::size($path) ?? 0;

                return [
                    'name' => $name,
                    'mime' => $mime,
                    'size' => $size,
                    'size_human' => $this->formatBytes($size),
                    'is_image' => $this->isImageMime($mime),
                    'open_url' => route('admin.uploads.storage.show', ['filename' => $name]),
                ];
            })->all()
            : [];

        $publicPath = public_path($this->publicDirectory);
        $publicFiles = File::isDirectory($publicPath)
            ? collect(File::files($publicPath))->map(function ($file) {
                $name = $file->getFilename();
                $mime = File::mimeType($file->getRealPath()) ?? 'application/octet-stream';
                $size = $file->getSize();

                return [
                    'name' => $name,
                    'mime' => $mime,
                    'size' => $size,
                    'size_human' => $this->formatBytes($size),
                    'is_image' => $this->isImageMime($mime),
                    'open_url' => route('admin.uploads.public.show', ['filename' => $name]),
                    'public_url' => asset($this->publicDirectory . '/' . $name),
                ];
            })->all()
            : [];

        return view('admin.uploads.index', [
            'storageDir' => $this->storageDirectory,
            'publicDir' => $this->publicDirectory,
            'storageFiles' => $storageFiles,
            'publicFiles' => $publicFiles,
        ]);
    }

    public function storeWithStorage(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,txt', 'max:2048'],
        ]);

        $file = $data['file'];
        $directory = $this->storageDirectory;

        if (! Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $filename = $this->generateFilename($file);
        $targetPath = $directory . '/' . $filename;

        if (Storage::exists($targetPath)) {
            $filename = $this->generateFilename($file, true);
            $targetPath = $directory . '/' . $filename;
        }

        Storage::putFileAs($directory, $file, $filename);

        $message = "Stored with Storage facade as {$targetPath}. Directory exists? "
            . (Storage::exists($directory) ? 'yes' : 'no');

        return redirect()->route('admin.uploads.index')->with('success', $message);
    }

    public function storeWithPublic(Request $request)
    {
        $data = $request->validate([
            'file_public' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,txt', 'max:2048'],
        ]);

        $file = $data['file_public'];
        $relativeDirectory = $this->publicDirectory;
        $directory = public_path($relativeDirectory);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = $this->generateFilename($file);
        $targetPath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($targetPath)) {
            $filename = $this->generateFilename($file, true);
            $targetPath = $directory . DIRECTORY_SEPARATOR . $filename;
        }

        $file->move($directory, $filename);

        $message = "Stored manually in public/{$relativeDirectory}/{$filename}. Directory existed? "
            . (File::exists($directory) ? 'yes' : 'no');

        return redirect()->route('admin.uploads.index')->with('success_public', $message);
    }

    private function generateFilename(UploadedFile $file, bool $alternate = false): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $slug = Str::slug($name) ?: 'file';
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(6);
        $prefix = $alternate ? 'alt' : 'main';
        // dd($name,$slug,$prefix);

        return "{$timestamp}_{$prefix}_{$slug}_{$random}." . strtolower($extension);
    }

    public function showStorage(string $filename)
    {
        $safeName = basename($filename);
        $path = $this->storageDirectory . '/' . $safeName;

        if (! Storage::exists($path)) {
            abort(404);
        }

        $mime = Storage::mimeType($path) ?? 'application/octet-stream';

        if ($this->isImageMime($mime)) {
            return Storage::response($path, $safeName, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $safeName . '"',
            ]);
        }

        return Storage::download($path, $safeName);
    }

    public function showPublic(string $filename)
    {
        $safeName = basename($filename);
        $path = public_path($this->publicDirectory . '/' . $safeName);

        if (! File::exists($path)) {
            abort(404);
        }

        $mime = File::mimeType($path) ?? 'application/octet-stream';

        if ($this->isImageMime($mime)) {
            return response()->file($path, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $safeName . '"',
            ]);
        }

        return response()->download($path, $safeName);
    }

    private function isImageMime(?string $mime): bool
    {
        return $mime !== null && Str::startsWith($mime, 'image/');
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = (int) floor((strlen((string) $bytes) - 1) / 3);
        $factor = min($factor, count($units) - 1);

        return sprintf('%.2f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }
}
