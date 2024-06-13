<?php

namespace App\Http\Controllers;

use App\Models\TokenInfo;
use App\Traits\ResponseErrorApiTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class YandexApiController extends Controller
{
    use ResponseErrorApiTrait;
    protected $tokenInfo;

    public function __construct(TokenInfo $tokenInfo)
    {
        $this->tokenInfo = $tokenInfo;
    }

    public function createWalkingCourier($date_of_birth,$first_name,$surname,$patronymic,$phone,$workRule)
    {
        $tokenInfo=$this->tokenInfo->getInfo();
        $idempotency_token=$this->tokenInfo->setRandToken();
  ;
        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v2/parks/contractors/walking-courier-profile';
        $headers = [
//            'X-Idempotency-Token' => $tokenInfo->idempotency_token,
            'X-Idempotency-Token' =>$idempotency_token,
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
            'work_rule_id' => $workRule,
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
                'idempotency_token' => $idempotency_token,
            ];

        } catch (\Exception $e) {

            $resp=$this->responseApiErrorTransform($e);
            return [
                'status' => $resp['status'],
                'data' => [
                    'message' => $resp['message'],
                ],
            ];
        }
    }

    public function createAvtoCourier($date_of_birth,$first_name,$surname,$patronymic,$phone,$workRule,$driverCountry,$license_expirated,$license_issue,$licenceNumber,$hire_date)
    {
        $tokenInfo=$this->tokenInfo->getInfo();
        $idempotency_token=$this->tokenInfo->setRandToken();

        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v2/parks/contractors/auto-courier-profile';
        $headers = [
            'X-Idempotency-Token' => $idempotency_token,
            'X-Client-ID' => $tokenInfo->client_id,
            'X-API-Key' => $tokenInfo->api_key,
            'X-Park-ID' => $tokenInfo->park_id,
        ];
        // Тело запроса
        $body = [
            "account" => [
        "balance_limit" => "5",
        "block_orders_on_balance_below_limit" => false,
        "work_rule_id" => $workRule
        ],
            "order_provider"=> [
        "partner"=> true,
        "platform"=> true
                               ],
            "person"=>
                [
                 "contact_info"=> [
        "phone"=> $phone
                                  ],
                "driver_license"=> [
        "country"=> $driverCountry,
        "expiry_date"=> $license_expirated,
        "issue_date"=> $license_issue,
        "number"=> $licenceNumber
                                   ],
                "driver_license_experience"=> [
        "total_since_date"=> $license_issue
                                              ],
                'full_name' => [
        'first_name' => $first_name,
        'last_name' => $surname,
        'middle_name' => $patronymic,
                               ],
                ],
                "profile"=> [
        "hire_date"=> $hire_date
                            ],

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
                'idempotency_token' => $idempotency_token,
            ];

        } catch (\Exception $e) {
            $resp=$this->responseApiErrorTransform($e);
            return [
                'status' => $resp['status'],
                'data' => [
                    'message' => $resp['message'],
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
            dd($e->getMessage);
            // Обработка ошибок
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
