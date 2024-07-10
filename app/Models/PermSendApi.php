<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermSendApi extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getPermToSendApi()
    {
       return PermSendApi::first()->value('accessApi');
    }
    public function setAccessApiStatus($data)
    {
        PermSendApi::where('id', '1')->update([
            'accessApi' =>$data,
        ]);
    }
}
