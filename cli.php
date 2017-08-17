<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

call_user_func(function () {
    $container = new \Search2d\Container(__DIR__);

    /** @var \Symfony\Component\Console\Application $app */
    $app = $container[\Symfony\Component\Console\Application::class];
    $app->run();
});
