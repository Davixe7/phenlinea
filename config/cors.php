<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],

    'allowed_origins' => ['http://localhost:8080', 'http://localhost:9000', 'http://localhost:3000', '*.phenlinea.com', 'http://192.168.0.246:9001', 'https://192.168.0.246:9001'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['X-Requested-With, Content-Type, X-Token-Auth, X-XSRF-TOKEN, Authorization'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
