<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey='offer_id';
    public function offerProducts()
    {
        return $this->hasMany(OfferProduct::class, 'offer_id');
    }
}
