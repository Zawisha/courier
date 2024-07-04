<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function carBrands()
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }



}
