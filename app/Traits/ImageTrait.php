<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public static function uploadImage($image, $path)
    {
        $path = $image->store($path, 'public');
        return $path;
    }

    public static function updateImage($object, $file, $input)
    {
        if (request()->hasFile($input)) {
            if ($object != null) {
                Storage::disk('public')->delete($object);
            }
            return $data[$input] = ImageTrait::uploadImage(request()->file($input), $file);
        } else {
            return $data[$input] = $object;
        }
    }
}
