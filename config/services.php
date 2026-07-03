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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', '8714018345:AAF09kJaaMdmqFagfncGof3IHLPwr0x6ziI'),
        'logger_chat_id' => env('TELEGRAM_LOGGER_CHAT_ID', '4774277831')
    ],

    'admin_credentials' => [
        'server_url' => 'https://abcerp.fanatech.net',
        'email' => 'adminmuaraselatan@gmail.com',
        'password' => 'admin123!',
    ],

    'is_onpremise' => env('IS_ONPREMISE', false),
    'pgsql' => [
        'service_name' => env('PGSQL_SERVICE_NAME', 'ABC POS PostgreSQL'),
        'bin_path' => env('PGSQL_BIN_PATH', base_path('pgsql/bin')),
        'data_path' => env('PGSQL_DATA_PATH', storage_path('pgsql/data')),
        'port' => env('DB_PORT', 5432),
        'superuser' => env('DB_USERNAME', 'bukanadmin'),
        'password'  => env('DB_PASSWORD', 'B15mi1Ll@h'),
        'database' => env('DB_DATABASE', 'abc_pos_db'),
    ]

    // 'on_premise_db_central_cred' => [
    //     'DB_CONNECTION' => env('DB_CONNECTION', 'pgsql'),
    //     'DB_HOST' => env('DB_HOST', '127.0.0.1'),
    //     'DB_PORT' => env('DB_PORT', '5432'),
    //     'DB_DATABASE' => env('DB_DATABASE', 'abcerp_central'),
    //     'DB_USERNAME' => env('DB_USERNAME', 'bukanadmin'),
    //     'DB_PASSWORD' => env('DB_PASSWORD', 'B15mi1Ll@h'),
    // ],
];
