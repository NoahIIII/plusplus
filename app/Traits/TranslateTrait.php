<?php

namespace App\Traits;


trait TranslateTrait
{
    function translate($request_ar, $request_en)
    {
        return [
            'en' => $request_en,
            'ar' => $request_ar,
        ];
    }
}
