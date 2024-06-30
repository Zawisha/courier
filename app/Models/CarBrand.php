<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    use HasFactory;
    protected $guarded = false;


    public function carModel()
    {
        return $this->hasMany(CarModel::class, 'brand_id');
    }
    public function getAllBrandWithModel()
    {
        return CarBrand::with('carModel')->get();
    }


}
