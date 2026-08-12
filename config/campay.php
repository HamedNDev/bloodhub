<?php

return [
    'token'        => env('CAMPAY_TOKEN'),
    'password'     => env('CAMPAY_PASSWORD'),
    'base_url'     => env('CAMPAY_BASE_URL', 'https://demo.campay.net/api'),
    'redirect_url' => env('CAMPAY_REDIRECT_URL', 'http://localhost:8000/paiements/callback'),
    'failure_url'  => env('CAMPAY_FAILURE_URL', 'http://localhost:8000/paiements/echec'),
];

