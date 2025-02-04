<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TranslateService
{

    /**
     * get localized value from json column
     * @param mixed $column_name
     * @param mixed $alias
     * @param mixed $unQuoted
     */
    public static function localizedColumn($column_name, $alias = null, $unQuoted = false)
    {
        if (!$alias) {
            $alias = $column_name;
        }
        $locale = app()->getLocale();
        if ($unQuoted) {
            return DB::raw("JSON_UNQUOTE(JSON_EXTRACT($column_name, '$.$locale')) as $alias");
        }
        return DB::raw("JSON_EXTRACT($column_name, '$.$locale') as $alias");
    }
    /**
     * Get translatable data from the request form
     *
     * @param array $fields
     * @return array
     */
    public static function getTranslatableData($data,array $fields)
    {
        foreach ($fields as $field) {
            $data[$field] = [
                'en' => request()->input("{$field}_en"),
                'ar' => request()->input("{$field}_ar"),
            ];
            unset($data[$field . '_en']);
            unset($data[$field . '_ar']);
        }
        return $data;
    }
}
