<?php

return [
    'api_endpoint' => env('AUCTION_API_ENDPOINT', ''),
    'api_password' => env('AUCTION_API_PASSWORD', ''),
    'method' => env('AUCTION_API_METHOD', 'gzip'),
    'response_type' => env('AUCTION_API_RESPONSE_TYPE', 'json')
];