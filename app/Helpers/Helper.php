<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * Translate a message and add missing translation keys to language files.
 *
 * @param $key
 * @param  array  $replace
 * @return string|array|null
 */
if (!function_exists('___')) {
    function ___($key, array $replace = [])
    {
        $trans_slug = Str::slug($key, '_');

        if (Lang::has('lang.' . $trans_slug)) {

            return trans('lang.' . $trans_slug, $replace, app()->getLocale());
        }

        /* Add Language key to all files if not exist */
        $allLanguages = File::directories(base_path('resources/lang'));
        foreach ($allLanguages as $language) {
            $filePath = $language . '/' . 'lang.php';

            if (File::exists($filePath)) {
                $translations = include $filePath;
            } else {
                $translations = [];
            }

            if (!array_key_exists($trans_slug, $translations)) {
                $translations[$trans_slug] = $key;
                File::put($filePath, "<?php\n\nreturn " . var_export($translations, true) . ";\n");
            }
        }

        foreach ($replace as $placeholder => $value) {
            $key = str_replace(':' . $placeholder, $value, $key);
        }

        return $key;
    }
}

/**
 * get full image url
 */
if (!function_exists('getImageUrl')) {
    function getImageUrl($image)
    {
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }
        return $image ? asset('storage/images/' . $image) : null;
    }
}


/**
 * Store Image
 */
if (!function_exists('storeImage')) {
    function storeImage($image, $path)
    {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path($path), $imageName);
        return $imageName;
    }
}
/**
 * Check if the current route matches the given route or pattern.
 *
 * @param string|array $routes The route(s) or pattern(s) to check.
 * @param string $class The class to return if the route matches.
 * @return string
 */
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routes, $class = 'active', $slug = null)
    {
        $routes = (array) $routes; // Ensure $routes is an array
        foreach ($routes as $route) {
            // Check if the route name matches and if the slug matches (if passed)
            if (request()->routeIs($route)) {
                // Check if the slug is provided and matches the current route slug
                if ($slug && request()->route('slug') != $slug) {
                    return ''; // Return the class if both match
                }
                // If no slug is provided, just check if the route matches
                return $class;
            }
        }
        return '';
    }
}

/**
 * get current business type from the url
 */
if (!function_exists('getBusinessType')) {
    function getBusinessType()
    {
        return request()->segment(1);
    }
}
