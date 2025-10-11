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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'news' => [
        'retrieval_interval_minutes' => env('NEWS_RETRIEVAL_INTERVAL_MINUTES'),

        'sources' => [
            'news_api' => [
                'api_key' => env('NEWSAPI_APIKEY'),
                'base_url' => env('NEWSAPI_BASE_URL'),
            ],

            'new_york_times' => [
                'api_key' => env('NEWYORKTIMES_APIKEY'),
                'base_url' => env('NEWYORKTIMES_BASE_URL'),
            ],

            'guardian' => [
                'api_key' => env('GUARDIANAPI_APIKEY'),
                'base_url' => env('GUARDIANAPI_BASE_URL'),
            ],
        ],
    ],

];
