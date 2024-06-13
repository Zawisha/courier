<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenInfo extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getInfo()
    {
         return TokenInfo::first();
    }

    public function setRandToken()
    {
        do {
            $token = $this->generateIdempotencyToken();
        } while ($this->tokenExistsInDatabase($token));

        return $token;
    }
    private function tokenExistsInDatabase($token)
    {
        return CourierInfo::where('idempotency_token', $token)->exists();
    }
    public function generateIdempotencyToken()
    {
        return hash('sha256', strval(uniqid()));
    }



}
