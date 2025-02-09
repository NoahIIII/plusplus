<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'status',
        'business_type_id',
        'parent_id',
        'level',
        'image'
    ];
    protected $primaryKey = 'category_id';



    //----------------------------- Accessors ------------------------------

    public function getImageAttribute($value)
    {
        if (request()->is('api/*')) {
            return getImageUrl($value);
        }
        return $value;
    }


    //-------------------------- Relations ----------------------------------

    // A category may have many subcategories
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('level', 2); // Get subcategories (Level 2)
    }

    // A subcategory may have many sub-subcategories
    public function subSubcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('level', 3); // Get sub-subcategories (Level 3)
    }

    // children relation
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    // parent relation
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function getParentNameAttribute()
    {
        // Check if the category has a parent and return the localized name (en or ar)
        if ($this->parent) {
            // Assuming you're using the 'name' column stored as JSON, we'll fetch the correct language
            $locale = app()->getLocale(); // This gets the current language set (either 'en' or 'ar')

            // Return the name in the specified locale, defaulting to 'en' if the field is not available
            return $this->parent->name[$locale] ?? $this->parent->name['en'];
        }

        return null; // If no parent exists, return null
    }


    public function pharmacyProducts()
    {
        return $this->morphedByMany(PharmacyProduct::class, 'categoryable');
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
