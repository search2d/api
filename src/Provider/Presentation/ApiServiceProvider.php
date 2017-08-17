<?php
declare(strict_types=1);

namespace Search2d\Provider\Presentation;

use League\Tactician\CommandBus;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Search2d\Presentation\Api\Action\Api\QueryImgAction;
use Search2d\Presentation\Api\Action\Api\QueryUrlAction;
use Search2d\Presentation\Api\Action\Api\SearchAction;
use Search2d\Presentation\Api\Helper;
use Slim\App;

class ApiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $this->registerActions($container);
        $this->registerHelper($container);
        $this->registerSlim($container);
    }

    /**
     * @param \Pimple\Container $container
     * @return void
     */
    private function registerActions(Container $container): void
    {
        $container[SearchAction::class] = function (Container $container) {
            return new SearchAction($container[CommandBus::class], $container[Helper::class]);
        };

        $container[QueryImgAction::class] = function (Container $container) {
            return new QueryImgAction($container[CommandBus::class], $container[Helper::class]);
        };

        $container[QueryUrlAction::class] = function (Container $container) {
            return new QueryUrlAction($container[CommandBus::class], $container[Helper::class]);
        };
    }

    /**
     * @param \Pimple\Container $container
     * @return void
     */
    private function registerHelper(Container $container): void
    {
        $container[Helper::class] = function (Container $_) {
            return new Helper();
        };
    }

    /**
     * @param \Pimple\Container $container
     * @return void
     */
    private function registerSlim(Container $container): void
    {
        $container['errorHandler'] = function (Container $container) {
            return function (ServerRequestInterface $request, ResponseInterface $response, \Exception $exception) use ($container) {
                /** @var \Psr\Log\LoggerInterface $logger */
                $logger = $container[LoggerInterface::class];
                $logger->error('キャッチされなかった例外', ['exception' => $exception]);

                /** @var \Search2d\Presentation\Api\Helper $helper */
                $helper = $container[Helper::class];
                return $helper->responseFailure($response, 500, 'Internal Server Error');
            };
        };

        $container[App::class] = function (Container $container) {
            $app = new App($container);

            $this->defineRoutes($app);
            $this->enableCors($app);

            return $app;
        };
    }

    /**
     * @param \Slim\App $app
     * @return void
     */
    private function defineRoutes(App $app): void
    {
        $app->post('/query/img', QueryImgAction::class)->setName('query.img');
        $app->post('/query/url', QueryUrlAction::class)->setName('query.url');
        $app->get('/search/{sha1:[a-zA-Z0-9]{40}}', SearchAction::class)->setName('search');
    }

    /**
     * @param \Slim\App $app
     * @return void
     */
    private function enableCors(App $app): void
    {
        $app->options('/{routes:.+}', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
            return $response;
        });

        $app->add(function (ServerRequestInterface $req, ResponseInterface $res, callable $next) {
            /** @var \Psr\Http\Message\ResponseInterface $response */
            $response = $next($req, $res);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        });
    }
}