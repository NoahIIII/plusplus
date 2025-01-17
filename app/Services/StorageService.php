<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    // ------------------------ Save Image File
    public static function storeImage($imgFile, $directory, $prefix = null)
    {
        // Sanitize file name
        $imgName = $prefix . uniqid() . '.' . $imgFile->getClientOriginalExtension();
        // Store the file
        $imgFile->storeAs('public/images/' . $directory, $imgName);

        return  $directory.'/'.$imgName;
    }

    // ------------------------ Delete Image File
    public static function deleteImage($path)
    {
        Storage::delete('public/images/' . $path);
    }

    //--------------------------- Save File Image & video
    public static function saveFile($file, $directory, $prefix = null)
    {
        $file_name = $prefix . uniqid() . '.' . $file->getClientOriginalExtension();

        // Determine the directory based on the file type
        if (strpos($file->getMimeType(), 'image') !== false) {
            // It's an image
            $directory .= '/images';
        } elseif (strpos($file->getMimeType(), 'video') !== false) {
            // It's a video
            $directory .= '/videos';
        } else {
            // Unsupported file type
            return false;
        }

        // Store the file
        $file->storeAs('public/media/' . $directory, $file_name);
        return $file_name;
    }

    // ------------------------ Delete File
    public static function deleteFile($path)
    {
        Storage::delete('public/media/' . $path);
    }
}
