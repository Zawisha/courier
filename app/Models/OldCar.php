<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldCar extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function createOldCar($userId,$carId)
    {
        OldCar::create([
            'user_id' => $userId,
            'car_id' => $carId,
        ]);
    }

}
