<?php

namespace App\Http\Controllers;

use App\Models\TokenInfo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class YandexApiController extends Controller
{
    //список id созданных курьеров
    //9af279b0a219a0b41d8b4ffc6555bc93
    //9af279b0a219a0b41d8b4ffc6555bc93

    protected $tokenInfo;

    public function __construct(TokenInfo $tokenInfo)
    {
        $this->tokenInfo = $tokenInfo;
    }

    public function createWalkingCourier($date_of_birth,$first_name,$surname,$patronymic,$phone)
    {
       $tokenInfo=$this->tokenInfo->getInfo();

        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v2/parks/contractors/walking-courier-profile';
        $headers = [
            'X-Idempotency-Token' => $tokenInfo->idempotency_token,
            'X-Client-ID' => $tokenInfo->client_id,
            'X-API-Key' => $tokenInfo->api_key,
            'X-Park-ID' => $tokenInfo->park_id,
        ];
        // Тело запроса
        $body = [
            'birth_date' => $date_of_birth,
            'full_name' => [
                'first_name' => $first_name,
                'last_name' => $surname,
                'middle_name' => $patronymic,
            ],
            'phone' => $phone,
            'work_rule_id' => '180326e5a0284483b4952d535b0b1d7b',
        ];
        try {
            // Отправьте POST-запрос
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            // Получите ответ и декодируйте его
            $responseBody = json_decode($response->getBody(), true);
            // Верните ответ
            return [
                'status' => $response->getStatusCode(),
                'data' => $responseBody,
            ];

        } catch (\Exception $e) {
            // Обработка ошибок
            return [
                'status' => 500,
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    public function getWorkRules()
    {
        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v1/parks/driver-work-rules?park_id=9d5bd73ca1f044fd8aeba739793870ab';
        $headers = [
            'X-Client-ID' => 'taxi/park/9d5bd73ca1f044fd8aeba739793870ab',
            'X-API-Key' => 'LvNTgRICgUDnytxRerDxcYOGRcjbZVyBWLkgZQL',
            'Accept-Language' => 'ru',
            // другие заголовки, если необходимо
        ];
        // Тело запроса
        $body = [
            'park_id' => '9d5bd73ca1f044fd8aeba739793870ab',
        ];
        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            // Получите ответ и декодируйте его
            $responseBody = json_decode($response->getBody(), true);
            dd($responseBody);
            // Верните ответ
            return response()->json($responseBody);

        } catch (\Exception $e) {
            // Обработка ошибок
            return response()->json([
                dd($e->getMessage()),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
