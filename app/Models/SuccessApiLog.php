<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessApiLog extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveLog($userInfo,$contractor_profile_id)
    {
        SuccessApiLog::create([
            'user_id' => $userInfo->id,
            'contractor_profile_id' => $contractor_profile_id,
        ]);
    }


}
