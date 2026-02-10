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

    'llmapi' => [
        'base_url' => env('LLMAPI_BASE_URL', 'https://app.llmapi.ai'),
        'chat_path' => env('LLMAPI_CHAT_PATH', '/v1/chat/completions'),
        'api_key' => env('LLMAPI_API_KEY'),
        'model' => env('LLMAPI_MODEL'),
        'provider_name' => env('LLMAPI_PROVIDER_NAME', 'LLMAPI'),
        'source' => env('LLMAPI_SOURCE'),
        'reasoning_effort' => env('LLMAPI_REASONING_EFFORT', ''),
    ],

    'openrouter' => [
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai'),
        'chat_path' => env('OPENROUTER_CHAT_PATH', '/api/v1/chat/completions'),
        'api_key' => env('OPENROUTER_API_KEY'),
        'model' => env('OPENROUTER_MODEL'),
        'provider_name' => 'OpenRouter',
    ],

];
