<?php

// (array) Runtime configuration
return [
    'test_mode'    => env('TEST_MODE', FALSE),
    'test_ip'      => env('TEST_IP', '0.0.0.0'),
    'maintenance'  => FALSE,
    'cache_buster' => '2022-09-02 10:59:42',
    'hash_key'     => env('HASH_KEY', '{a-unique-key}'),
    'project_id'   => 'intrepid',
    'mx'           => [
        'api_key'   => env('MX_API_KEY'),
        'client_id' => env('MX_CLIENT_ID'),
        'test_mode' => env('MX_TEST_MODE'),
    ],
    'hooks' => [
        'dev' => [
            'auto_login' => TRUE,
        ],
    ],
    'client' => [
        'driver' => 'curl',
    ],
    'cookies' => [
        'path'     => '/',
        'domain'   => $_SERVER['SERVER_NAME'] ?? '',
        'secure'   => FALSE,
        'httponly' => TRUE,
    ],
    'crypto' => [
        'key'  => env('CRYPTO_KEY', '{a-cryptographically-secure-key}'),
        'flag' => env('CRYPTO_FLAG', '{a-unique-key}'),
        'auto' => TRUE,
    ],
    'db' => [
        'driver'             => 'mysql',
        'database'           => env('DB_NAME', 'test_app'),
        'host'               => env('DB_HOST', 'localhost'),
        'port'               => env('DB_PORT', 3306),
        'socket'             => env('DB_SOCK'),
        'username'           => env('DB_USER', 'test_app'),
        'password'           => env('DB_PASS', 'test_password'),
        'elevate_exceptions' => TRUE,
        'log_failed'         => TRUE,
        'log_success'        => FALSE,
        'log_limit'          => 1000,
    ],
    'debug' => [
        'test_mode' => [
            'always_display' => env('TELLSY_ON_TEST', TRUE),
        ],
        'test_ip' => [
            'always_display' => env('TELLSY_ON_IP', TRUE),
        ],
    ],
    'dom' => [
        'viewer_uri'      => '__dom',
        'markup_language' => 'html',
        'prep_engine'     => 'lexer',
        'prep_cache'      => FALSE,
        'csrf_tokens'     => TRUE,
        'remove_messages' => TRUE,
        'meta_no_cache'   => FALSE,
        'widgets_js'      => FALSE,
        'lexer'           => [
            'chunk_hash_map'  => TRUE,
            'chunk_hash_salt' => env('DOM_LEXER_HASH_SALT'),
        ],
    ],
    'emails' => [
        'driver'     => 'smtp',
        'from'       => env('MAIL_FROM', 'App Name <email@example.com>'),
        'reply_to'   => env('MAIL_REPLY', 'App Name <email@example.com>'),
        'debug_logs' => env('MAIL_LOGS', FALSE),
        'test_mode'  => env('MAIL_TEST', TRUE),
        'test_email' => env('MAIL_ADDR', 'Test Account <test@example.com>'),
        'smtp'       => [
            'host'     => env('SMTP_HOST'),
            'port'     => env('SMTP_PORT', 587),
            'username' => env('SMTP_USER'),
            'password' => env('SMTP_PASS'),
            'security' => NULL,
            'timeout'  => 60,
        ],
    ],
    'errors' => [
        'display'   => TRUE,
        'reporting' => E_ALL,
        'default'   => "We're sorry, but the system was unable to complete your request.",
    ],
    'logs' => [
        'file_dates' => 'Y',
        'debug'      => TRUE,
        'errors'     => TRUE,
        'events'     => TRUE,
        'exceptions' => TRUE,
    ],
    'sessions' => [
        'driver' => 'file',
        'length' => 32,
        'cookie' => [
            'lifetime' => 3600,
            'path'     => '/',
            'domain'   => $_SERVER['SERVER_NAME'] ?? '',
            'secure'   => FALSE,
            'httponly' => TRUE,
        ],
    ],
];
