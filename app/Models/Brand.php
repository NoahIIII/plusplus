<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];
    protected $table = 'brands';
    protected $primaryKey = 'brand_id';

    protected $fillable = [
        'name',
        'image',
        'status',
        'business_type_id'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Accessor to format the date when retrieved.
     *
     * @param  string  $value
     * @return string
     */
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    // You can create a similar accessor for any other date attribute
    public function getFormattedUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
