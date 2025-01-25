<?php

namespace App\Services;

use App\Models\BusinessType;

class BusinessTypeService
{
    /**
     * determine Business Type Slug based on the request url
     */
    public function determineBusinessTypeSlug()
    {
        $segments = request()->segments();
        $locales = config('app.locales', ['en', 'ar']);

        return in_array($segments[0] ?? null, $locales)
            ? $segments[1] ?? ''
            : $segments[0] ?? '';
    }

    /**
     * get business type id
     * @param mixed $slug
     */
    public function getBusinessId($slug)
    {
        return BusinessType::where('slug', $slug)
        ->value('id');
    }
}
