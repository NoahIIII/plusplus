<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'package_type',
        'unit_type',
        'unit_quantity',
        'stock_quantity',
        'price',
    ];



}
