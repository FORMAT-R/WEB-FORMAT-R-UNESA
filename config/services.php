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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'clipdrop' => [
        'key_1' => env('CLIPDROP_KEY_1'),
        'key_2' => env('CLIPDROP_KEY_2'),
        'key_3' => env('CLIPDROP_KEY_3'),
    ],

    'remove_bg' => [
        'key_1' => env('REMOVE_BG_KEY_1'),
        'key_2' => env('REMOVE_BG_KEY_2'),
        'key_3' => env('REMOVE_BG_KEY_3'),
    ],

];
