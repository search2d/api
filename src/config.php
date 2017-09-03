<?php
declare(strict_types=1);

return [
    'database' => [
        'dsn' => env_str('API_DATABASE_DSN'),
    ],
    'logger' => [
        'name' => env_str('API_LOGGER_NAME'),
        'stream' => [
            'enabled' => env_bool('API_LOGGER_STREAM_ENABLED', false),
            'path' => env_str('API_LOGGER_STREAM_PATH', ''),
            'level' => env_str('API_LOGGER_STREAM_LEVEL', ''),
        ],
        'fluent' => [
            'enabled' => env_bool('API_LOGGER_FLUENT_ENABLED', false),
            'tag' => env_str('API_LOGGER_FLUENT_TAG', ''),
            'host' => env_str('API_LOGGER_FLUENT_HOST', ''),
            'port' => env_str('API_LOGGER_FLUENT_PORT', ''),
            'level' => env_str('API_LOGGER_FLUENT_LEVEL', ''),
            'uid_path' => env_str('API_LOGGER_FLUENT_UID_PATH', ''),
            'log_group_name' => env_str('API_LOGGER_FLUENT_LOG_GROUP_NAME', ''),
        ],
    ],
    'router' => [
        'cache_file' => env_str('API_ROUTER_CACHE_FILE', ''),
        'cache_disabled' => env_bool('API_ROUTER_CACHE_DISABLED', true),
    ],
    'search' => [
        's3' => [
            'region' => env_str('API_SEARCH_S3_REGION'),
            'indexed_image_bucket' => env_str('API_SEARCH_S3_INDEXED_IMAGE_BUCKET'),
            'queried_image_bucket' => env_str('API_SEARCH_S3_QUERIED_IMAGE_BUCKET'),
        ],
        'nns' => [
            'base_uri' => env_str('API_SEARCH_NNS_BASE_URI'),
            'timeout' => env_float('API_SEARCH_NNS_TIMEOUT', 5.0),
            'connection_timeout' => env_float('API_SEARCH_NNS_TIMEOUT', 3.0),
        ],
        'fetcher' => [
            'timeout' => env_float('API_SEARCH_FETCHER_TIMEOUT', 5.0),
            'connection_timeout' => env_float('API_SEARCH_FETCHER_TIMEOUT', 3.0),
        ],
    ],
    'pixiv' => [
        'api' => [
            'username' => env_str('API_PIXIV_API_USERNAME'),
            'password' => env_str('API_PIXIV_API_PASSWORD'),
            'client_id' => env_str('API_PIXIV_API_CLIENT_ID'),
            'client_secret' => env_str('API_PIXIV_API_CLIENT_SECRET'),
            'delay' => env_int('API_PIXIV_API_DELAY', 1500),
            'timeout' => env_float('API_PIXIV_API_TIMEOUT', 10.0),
            'connection_timeout' => env_float('API_PIXIV_API_TIMEOUT', 10.0),
            'proxy' => env_str('API_PIXIV_API_PROXY', ""),
        ],
        'sqs' => [
            'region' => env_str('API_PIXIV_SQS_REGION'),
            'request_illust_url' => env_str('API_PIXIV_SQS_REQUEST_ILLUST_URL'),
            'request_ranking_url' => env_str('API_PIXIV_SQS_REQUEST_RANKING_URL'),
        ],
    ],
];