<?php

namespace App\Http\Controllers;

use App\Traits\S3FileHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    use S3FileHandler;
    /**
     * List all files in 'public' disk.
     */
    public function index()
    {
        // Get all files under storage/app/public
        $files = Storage::files('public');

        // Strip "public/" from the filenames
        $files = array_map(function($file) {
            return str_replace('public/', '', $file);
        }, $files);

        return response()->json($files);
    }

    /**
     * Store uploaded files.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('files')) {
            $uploadedFiles = [];
            foreach ($request->file('files') as $file) {
                // Store file in 'public' disk (storage/app/public)
                $path = $this->uploadFile($file);
                // Grab the basename (actual filename)
                $uploadedFiles[] = $path;
            }
            return response()->json(['uploaded' => $uploadedFiles], 200);
        }
        return response()->json(['message' => 'No files uploaded'], 400);
    }

    /**
     * Download or display the file.
     */
    public function show($filename)
    {
        $this->getFile($filename);
    }

    /**
     * Delete the file.
     */
    public function destroy($filename)
    {
        $this->deleteFile($filename);
        return response()->json(['message' => 'File deleted'], 200);
    }
}
