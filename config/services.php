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

    'llm' => [
        // auto: prefer LLMAPI when LLMAPI_API_KEY is set, otherwise OpenRouter
        // llmapi|openrouter: force a provider
        'provider' => env('LLM_PROVIDER', 'auto'),
    ],

    'llmapi' => [
        'base_url' => env('LLMAPI_BASE_URL', 'https://app.llmapi.ai'),
        'chat_path' => env('LLMAPI_CHAT_PATH', '/v1/chat/completions'),
        'api_key' => env('LLMAPI_API_KEY'),
        'model' => env('LLMAPI_MODEL'),
        'provider_name' => env('LLMAPI_PROVIDER_NAME', 'LLMAPI'),
        'source' => env('LLMAPI_SOURCE'),
        // Backward-compatible default (used when per-model value isn't set)
        'reasoning_effort' => env('LLMAPI_REASONING_EFFORT', ''),
        // Optional: set different effort depending on model family
        'reasoning_effort_gpt' => env('LLMAPI_REASONING_EFFORT_GPT', ''),
        'reasoning_effort_other' => env('LLMAPI_REASONING_EFFORT_OTHER', ''),
        'temperature' => env('LLMAPI_TEMPERATURE', ''),
        'max_tokens' => env('LLMAPI_MAX_TOKENS', ''),
        'top_p' => env('LLMAPI_TOP_P', ''),
        'frequency_penalty' => env('LLMAPI_FREQUENCY_PENALTY', ''),
        'response_format' => env('LLMAPI_RESPONSE_FORMAT', ''),
    ],

    'openrouter' => [
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai'),
        'chat_path' => env('OPENROUTER_CHAT_PATH', '/api/v1/chat/completions'),
        'api_key' => env('OPENROUTER_API_KEY'),
        'model' => env('OPENROUTER_MODEL'),
        'provider_name' => env('OPENROUTER_PROVIDER_NAME', 'OpenRouter'),
        'temperature' => env('OPENROUTER_TEMPERATURE', ''),
        'max_tokens' => env('OPENROUTER_MAX_TOKENS', ''),
        'top_p' => env('OPENROUTER_TOP_P', ''),
        'frequency_penalty' => env('OPENROUTER_FREQUENCY_PENALTY', ''),
        'response_format' => env('OPENROUTER_RESPONSE_FORMAT', ''),
    ],

];
