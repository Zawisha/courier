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

    public function createWalkingCourier($date_of_birth, $first_name, $surname, $patronymic, $phone, $workRule)
    {
        $tokenInfo = $this->tokenInfo->getInfo();
        $idempotency_token = $this->tokenInfo->setRandToken();;
        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v2/parks/contractors/walking-courier-profile';
        $headers = [
//            'X-Idempotency-Token' => $tokenInfo->idempotency_token,
            'X-Idempotency-Token' => $idempotency_token,
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

            $resp = $this->responseApiErrorTransform($e);
            return [
                'status' => $resp['status'],
                'data' => [
                    'message' => $resp['message'],
                ],
            ];
        }
    }

    public function editWalkingCourier($date_of_birth, $first_name, $surname, $patronymic, $phone, $workRule, $contractor_profile_id)
    {
        $tokenInfo = $this->tokenInfo->getInfo();
        $idempotency_token = $this->tokenInfo->setRandToken();

        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v2/parks/contractors/driver-profile';
        $headers = [
            'X-Idempotency-Token' => $idempotency_token,
            'X-Client-ID' => $tokenInfo->client_id,
            'X-API-Key' => $tokenInfo->api_key,
            'X-Park-ID' => $tokenInfo->park_id,
        ];

        // Тело запроса
        $body = [
            'contractor_profile_id' => $contractor_profile_id,
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

            $resp = $this->responseApiErrorTransform($e);
            return [
                'status' => $resp['status'],
                'data' => [
                    'message' => $resp['message'],
                ],
            ];
        }

    }

    public function createAvtoCourier($date_of_birth, $first_name, $surname, $patronymic, $phone, $workRule, $driverCountry, $license_expirated, $license_issue, $licenceNumber, $hire_date, $carId)
    {
        $tokenInfo = $this->tokenInfo->getInfo();
        $idempotency_token = $this->tokenInfo->setRandToken();

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
            "order_provider" => [
                "partner" => true,
                "platform" => true
            ],
            "person" =>
                [
                    "contact_info" => [
                        "phone" => $phone
                    ],
                    "driver_license" => [
                        "country" => $driverCountry,
                        "expiry_date" => $license_expirated,
                        "issue_date" => $license_issue,
                        "number" => $licenceNumber
                    ],
                    "driver_license_experience" => [
                        "total_since_date" => $license_issue
                    ],
                    'full_name' => [
                        'first_name' => $first_name,
                        'last_name' => $surname,
                        'middle_name' => $patronymic,
                    ],
                ],
            "profile" => [
                "hire_date" => $hire_date
            ],
            "car_id" => $carId
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
            $resp = $this->responseApiErrorTransform($e);
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

    public function getCars()
    {
        $tokenInfo = $this->tokenInfo->getInfo();

        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v1/parks/cars/list';
        $headers = [
            'X-Client-ID' => $tokenInfo->client_id,
            'X-API-Key' => $tokenInfo->api_key,
        ];

        // Тело запроса
        $body = [
            "limit" => 100,
            "offset" => 0,
            "fields" =>
                [
                    "car" => [
                        "id", "status","amenities","category","callsign","brand","model","year","color","number","registration_cert","vin",
                    ],
                ],
            "query" =>
                [
                    "park" => [
                        "id" => $tokenInfo->client_id
                    ],

                ],
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
            // Получите ответ и декодируйте его
            $responseBody = json_decode($response->getBody(), true);
            dd($responseBody);
            // Верните ответ
            return response()->json($responseBody);
        } catch (\Exception $e) {
            dd($e);
            // Обработка ошибок
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }



    }

    public function createCar($role, $categories, $boosterCount, $phone, $licencePlateNumber, $registrationCertificate, $brandTS, $modelTS, $colorAvto, $transmission, $vin, $carManufactureYear, $cargoHeight, $cargoLength, $cargoWidth, $cargoLoaders,$cargoCapacity,$motoPhone)
    {
        $tokenInfo = $this->tokenInfo->getInfo();
        $idempotency_token = $this->tokenInfo->setRandToken();

        if ($role == 'moto') {
            $licencePlateNumber=$motoPhone;
            $registrationCertificate=$motoPhone;
        }

        $client = new Client();
        $url = 'https://fleet-api.taxi.yandex.net/v2/parks/vehicles/car';
        $headers = [
            'X-Idempotency-Token' => $idempotency_token,
            'X-Client-ID' => $tokenInfo->client_id,
            'X-API-Key' => $tokenInfo->api_key,
            'X-Park-ID' => $tokenInfo->park_id,
        ];
        // Тело запроса
        $body = [
            "child_safety" => [
                "booster_count" => 0
             ],
            "park_profile" =>
                [
                    "callsign" => $phone,
                    "status" => 'unknown',
                    "categories" => $categories,
                ],
            "vehicle_licenses" =>
                [
                    "licence_plate_number" => $licencePlateNumber,
                    "registration_certificate" => $registrationCertificate,
                ],
            "vehicle_specifications" =>
                [
                    "model" => $modelTS,
                    "brand" => $brandTS,
                    "color" => $colorAvto,
                    "transmission" => $transmission,
                    "vin" => $vin,
                    "year" => (int)$carManufactureYear,
                ],
        ];

        if ($role == 'gruz') {
            $body["cargo"] = [
                "cargo_hold_dimensions" => [
                    "height" => (int)$cargoHeight,
                    "length" => (int)$cargoLength,
                    "width" => (int)$cargoWidth,
                ],
                "cargo_loaders" => (int)$cargoLoaders,
                "carrying_capacity" => (int)$cargoCapacity,
            ];
        }

        try {
            // Отправьте POST-запрос
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            // Получите ответ и декодируйте его
            $responseBody = json_decode($response->getBody(), true);
            //dd($responseBody);
            // Верните ответ
            return [
                'status' => $response->getStatusCode(),
                'data' => $responseBody,
                'idempotency_token' => $idempotency_token,
            ];

        } catch (\Exception $e) {
           // dd($e);
            $resp = $this->responseApiErrorTransform($e);
            return [
                'status' => $resp['status'],
                'data' => [
                    'message' => $resp['message'],
                ],
            ];
        }
    }

}
