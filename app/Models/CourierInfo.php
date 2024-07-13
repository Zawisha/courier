<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierInfo extends Model
{
    use HasFactory;
    protected $table = 'courier_info';
    protected $guarded = false;

    public function createCourier($request,$userInfo,$roleId,$idempotency_token,$creatadCarId,$sended_to_yandex)
    {
        CourierInfo::create([
            'user_id' => $userInfo->id,
            'role_id' => $roleId,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'date_of_birth' => $request->date_of_birth,
            'licenceNumber' => $request->licenceNumber,
            'license_issue' => $request->license_issue,
            'license_expirated' => $request->license_expirated,
            'telegram' => $request->telegram,
            'work_rule_id' => $request->workRule,
            'idempotency_token' => $idempotency_token,
            'car_id'=>$creatadCarId,
            'sended_to_yandex'=>$sended_to_yandex,
            'driverCountry'=>$request->driverCountry,
        ]);
    }

}
