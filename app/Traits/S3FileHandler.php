<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait S3FileHandler
{
    /**
     * Uploads a file to the specified directory on S3.
     * 
     * @param UploadedFile $file The file to upload.
     * @param string $directory The directory to upload the file to. Defaults to "commons".
     * @param string|null $name The name of the file. If not provided, a unique name will be generated.
     * @throws Some_Exception_Class If the upload fails.
     * @return string The path to the uploaded file on S3.
     */
    public function uploadFile(UploadedFile $file, $directory="commons", $name=null)
    {
        $filename = $name ? $name : uniqid().'-' . $file->getClientOriginalName();
        Storage::disk('s3')->put($directory.'/'.$filename, file_get_contents($file));
        
        return "/files"."/".$directory.'/'.$filename;
    }

    public function deleteFile($path)
    {
        Storage::disk('s3')->delete($path);
    }

    public function getFile($path)
    {
        return Storage::disk('s3')->get($path);
    }
}