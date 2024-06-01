<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCourier extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function getAllCourierStatus()
    {
        return StatusCourier::all();
    }
    public function getStatusId($value_status_req)
    {
     return StatusCourier::where('value_status', $value_status_req)->pluck('id');
    }

}
