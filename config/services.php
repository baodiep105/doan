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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => '1076580330230-o65dalqp4773782q1r0fh3nlcr86csnm.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://doan-production.up.railway.app/api/google/callback',
    ],
    'facebook' => [
        'client_id' => '893394775297643',
        'client_secret' => 'd0fc42c08a8ad6285f1349df06a44301',
        'redirect' => 'https://doan-production.up.railway.app/api/facebook/callback',
    ],
];
