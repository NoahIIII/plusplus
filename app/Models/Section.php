<?php

namespace App\Models;

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
        'upper_color',
        'lower_color',
    ];

    public function sectionProducts()
    {
        return $this->hasMany(SectionProduct::class, 'section_id');
    }
}
