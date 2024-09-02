<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckClient extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function checkClientModel($clientName)
    {
        return CheckClient::where('client_name', $clientName)->exists();
    }
    public function addClient($clientName)
    {
        CheckClient::create([
            'client_name' => $clientName,
        ]);
    }


}
