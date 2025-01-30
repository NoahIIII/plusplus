<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $guarded = ['discount_id'];
    protected $primaryKey = 'discount_id';

    // relations
    public function productDiscounts()
    {
        return $this->hasMany(ProductDiscount::class, 'discount_id');
    }
    /**
     * Scope to filter discounts to get the not coupon discounts
     */
    public function scopeDiscount($query)
    {
        return $query->where('apply_on', 'product')->whereNull('code');
    }
    /**
     * Scope to filter discounts to get the coupon discounts
     */
    public function scopeCoupon($query)
    {
        return $query->where('apply_on', 'order')->whereNotNull('code');
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
    public function getFormattedStartDateAttribute()
    {
        return Carbon::parse($this->attributes['start_date'])->format('Y-m-d');
    }
    public function getFormattedEndDateAttribute()
    {
        return Carbon::parse($this->attributes['end_date'])->format('Y-m-d');
    }
}
