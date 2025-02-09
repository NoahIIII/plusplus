<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PharmacyProduct extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'description'];
    protected $table = 'pharmacy_products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'quantity',
        'price',
        'status',
        'primary_image',
    ];

    //-------------- relations
    public function media()
    {
        return $this->morphMany(ProductMedia::class, 'mediable');
    }
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable', 'product_categories', 'categoryable_id', 'category_id');
    }

    public function packageTypes()
    {
        return $this->hasMany(PackageType::class, 'product_id');
    }
    public function sections()
    {
        return $this->morphMany(SectionProduct::class, 'productable');
    }
    //-------------- Accessors
    public function getPrimaryImageAttribute($value)
    {
        if (request()->is('api/*')) {
            return getImageUrl($value);
        }
        return $value;
    }
}
