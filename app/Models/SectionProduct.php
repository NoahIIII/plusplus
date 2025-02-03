<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionProduct extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function productable()
    {
        return $this->morphTo();
    }
}
