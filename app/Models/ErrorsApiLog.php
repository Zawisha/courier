<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorsApiLog extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveError($request,$roleId,$response)
    {
        ErrorsApiLog::create([
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
            'error_message' => $response['data']['message'],
        ]);
    }


}
