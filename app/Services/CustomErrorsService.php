<?php

namespace App\Services;

class CustomErrorsService
{

    public function errorMessage($response)
    {
        $message = '';
        if ($response['status'] == 400) {
            $message = __('validation.custom_error_400');
            if($response['data']['message']=='invalid_driver_license')
            {
                $message = __('validation.custom_error_400_license');
            }
            if($response['data']['message']=='duplicate_phone')
            {
                $message = __('validation.custom_error_400_phone');
            }
            if($response['data']['message']=='unexcepted symbols in car number')
            {
                $message = __('validation.custom_error_400_car_number');
            }
        } elseif ($response['status'] == 429) {
            $message = __('validation.custom_error_429');
        } elseif ($response['status'] == 500) {
            $message = __('validation.custom_error_500');
        }
        return $message;
    }
    public function errorMessageAvto($response)
    {
        $message = $response['data']['message'];
            if($response['data']['message']=='invalid_driver_license')
            {
                $message = __('validation.custom_error_400_license');
            }
            if($response['data']['message']=='duplicate_phone')
            {
                $message = __('validation.custom_error_400_phone');
            }
            if($response['data']['message']=='unexcepted symbols in car number')
            {
                $message = __('validation.custom_error_400_car_number');
            }
       if ($response['status'] == 429) {
            $message = __('validation.custom_error_429');
        }
       if ($response['status'] == 500) {
            $message = __('validation.custom_error_500');
        }
        return $message;
    }
}
