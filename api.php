<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

call_user_func(function () {
    $container = new \Search2d\Container(__DIR__);

    /** @var \Search2d\Infrastructure\Logger\ExceptionHandler $exceptionHandler */
    $exceptionHandler = $container[\Search2d\Infrastructure\Logger\ExceptionHandler::class];
    $exceptionHandler->install();

    /** @var \Search2d\Infrastructure\Presentation\Api\Frontend $frontend */
    $frontend = $container[\Search2d\Infrastructure\Presentation\Api\Frontend::class];
    $response = $frontend->handle(\Zend\Diactoros\ServerRequestFactory::fromGlobals(), new \Zend\Diactoros\Response());

    $emitter = new \Zend\Diactoros\Response\SapiEmitter();
    $emitter->emit($response);
});
