<?php

namespace App\Services;

class CustomErrorsService
{

    public function errorMessage($response)
    {
        $message = '';
        if ($response['status'] == 400) {
            $message = __('validation.custom_error_400');
        } elseif ($response['status'] == 429) {
            $message = __('validation.custom_error_429');
        } elseif ($response['status'] == 500) {
            $message = __('validation.custom_error_500');
        }
        return $message;
    }

}
