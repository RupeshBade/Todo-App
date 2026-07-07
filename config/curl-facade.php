<?php

return [
    'connections' => [
        'mock_api' => [
            'base_url'   => env('MOCK_API_URL', 'https://6a2912e8f59cb8f65f1c674f.mockapi.io/api/v1'),
            'app_key'    => env('MOCK_API_APP_KEY'),
            'key_secret' => env('MOCK_API_KEY_SECRET'),
        ],
        'jsonplaceholder' => [
            'base_url' => env('JSON_API_URL', 'https://jsonplaceholder.typicode.com'),
        ],
        'mock_api_2' => [
            'base_url'   => env('MOCK_API_2_URL', 'https://6a2912e8f59cb8f65f1c674f.mockapi.io/api/v1'),
            'app_key'    => env('MOCK_API_APP_KEY'),
            'key_secret' => env('MOCK_API_KEY_SECRET'),
        ],
    ],
];
