<?php
return [
    'bkash_base_url'     => env("BKASH_TOKENIZE_BASE_URL"),
    'bkash_callback_url' => env("BKASH_TOKENIZE_CALLBACK_URL"),
    'bkash_api_version'  => env("BKASH_TOKENIZE_VERSION", "v1.2.0-beta"),
    'bkash_app_key'      => env("BKASH_TOKENIZE_APP_KEY", ""),
    'bkash_app_secret'   => env("BKASH_TOKENIZE_APP_SECRET", ""),
    'bkash_username'     => env("BKASH_TOKENIZE_USER_NAME", ""),
    'bkash_password'     => env("BKASH_TOKENIZE_PASSWORD", ""),
];
