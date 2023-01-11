<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
     */

    'mailgun'  => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses'      => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'ssl'      => [
        'process'      => env('SSL_PROCESS'),
        'check'        => env('SSL_CHECK'),
        'js'           => env('SSL_JS'),
        'shop_name'    => env('SSL_SHOP_NAME'),
        'store_id'     => env('SSL_STORE_ID'),
        'store_passwd' => env('SSL_STORE_PASSWD'),
    ],

    'facebook' => [
        'client_id'     => "1193594831109845", // configure with your app id
        'client_secret' => 'd060b842f87e52d6834fb7851f41d680', // your app secret
        'redirect'      => 'https://boiferry.com/auth/facebook/callback', // IMPORTANT NOT REMOVE /oauth/facebook/callback
    ],

    'google'   => [
        'client_id'     => '60691111493-p8ijn365lfmj9267m44gkpd63qa4fuvc.apps.googleusercontent.com',
        'client_secret' => 'r2fvWyK7oqTDoAo2MDQF6en1',
        'redirect'      => 'https://boiferry.com/auth/google/callback', // IMPORTANT NOT REMOVE /oauth/google/callback
    ],

];
