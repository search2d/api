<?php
declare(strict_types=1);

return [
    'database' => [
        'dsn' => env_str('API_DATABASE_DSN'),
    ],
    'logger' => [
        'name' => env_str('API_LOGGER_NAME'),
        'path' => env_str('API_LOGGER_PATH'),
        'level' => env_str('API_LOGGER_LEVEL'),
        'cwl' => [
            'region' => env_str('API_LOGGER_CWL_REGION', ''),
            'group' => env_str('API_LOGGER_CWL_GROUP', ''),
            'stream' => env_str('API_LOGGER_CWL_STREAM', ''),
            'retention_days' => env_int('API_LOGGER_CWL_RETENTION_DAYS', 7),
            'batch_size' => env_int('API_LOGGER_CWL_BATCH_SIZE', 1),
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
            'indexed_image_base_url' => env_str('API_SEARCH_S3_INDEXED_IMAGE_BASE_URL'),
            'queried_image_bucket' => env_str('API_SEARCH_S3_QUERIED_IMAGE_BUCKET'),
            'queried_image_base_url' => env_str('API_SEARCH_S3_QUERIED_IMAGE_BASE_URL'),
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