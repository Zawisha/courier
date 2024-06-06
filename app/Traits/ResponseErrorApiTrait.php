<?php
namespace App\Traits;
use App\Models\StatusCourier;

trait ResponseErrorApiTrait
{

    public function responseApiErrorTransform($e)
    {
        $responseBody = $e->getResponse()->getBody()->getContents();
        $errorData = json_decode($responseBody, true);
        $status = $errorData['code'];
        $message = $errorData['message'];
        // Обработка ошибок
        return [
            'status' => $status,
            'message' => $message,
        ];
    }

}
