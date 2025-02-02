<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class TranslateService{

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
}
