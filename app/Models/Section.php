<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasFactory;
    use HasTranslations;
    protected $table = 'sections';
    protected $primaryKey = 'section_id';
    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'business_type_id',
        'status',
        'first_color',
        'second_color',
    ];

    public function sectionProducts()
    {
        return $this->hasMany(SectionProduct::class, 'section_id');
    }
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
