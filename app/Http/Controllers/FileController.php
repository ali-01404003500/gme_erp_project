<?php

namespace App\Http\Controllers;

use App\Traits\S3FileHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use S3FileHandler;
    
    public function getFile($path)
    {
        $content = Storage::disk('public')->get($path);
        return response($content)->header('Content-Type', $this->getMimeType($path));
    }


    
    private function getMimeType($filename)
    {
        $ext = substr(strrchr($filename, '.'), 1);
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];
        return $mimeTypes[$ext] ?? 'application/octet-stream';
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);
        $path = $this->uploadFile($request->file);
        return response()->json(['path' => $path], 200);
    }


    public function destroyFile(Request $request)
    {

        $this->deleteFile($request->path);
        return response()->json(['message' => 'File deleted successfully'], 200);
    }
    
}
