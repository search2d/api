<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

call_user_func(function () {
    $container = new \Search2d\Container(__DIR__);

    /** @var \Slim\App $app */
    $app = $container[\Slim\App::class];
    $app->run();
});
