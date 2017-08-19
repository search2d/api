<?php
declare(strict_types=1);

namespace Search2d\Provider\Presentation;

use FastRoute\RouteCollector;
use League\Tactician\CommandBus;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Infrastructure\Presentation\Api\ActionMiddleware;
use Search2d\Infrastructure\Presentation\Api\OptionsResponderMiddleware;
use Search2d\Infrastructure\Presentation\Api\Frontend;
use Search2d\Infrastructure\Presentation\Api\RouterMiddleware;
use Search2d\Presentation\Api\Action\Api\QueryImgAction;
use Search2d\Presentation\Api\Action\Api\QueryUrlAction;
use Search2d\Presentation\Api\Action\Api\SearchAction;
use Search2d\Presentation\Api\Helper;
use function FastRoute\cachedDispatcher;

class ApiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $this->registerFrontend($container);
        $this->registerActions($container);
        $this->registerHelper($container);
    }

    /**
     * @param \Pimple\Container $container
     * @return void
     */
    private function registerFrontend(Container $container): void
    {
        $container[Frontend::class] = function (Container $container) {
            return new Frontend([
                $container[OptionsResponderMiddleware::class],
                $container[RouterMiddleware::class],
                $container[ActionMiddleware::class],
            ]);
        };

        $container[OptionsResponderMiddleware::class] = function (Container $container) {
            return new OptionsResponderMiddleware();
        };

        $container[RouterMiddleware::class] = function (Container $container) {
            $router = $container['config']->router;
            $dispatcher = cachedDispatcher(function (RouteCollector $r) {
                $this->routeDefinition($r);
            }, [
                'cacheFile' => $router->cache_file,
                'cacheDisabled' => $router->cache_disabled,
            ]);
            return new RouterMiddleware($dispatcher);
        };

        $container[ActionMiddleware::class] = function (Container $container) {
            return new ActionMiddleware(
                function (string $name) use ($container): callable {
                    return $container[$name];
                },
                function (ServerRequestInterface $request, ResponseInterface $response) use ($container): ResponseInterface {
                    /** @var \Search2d\Presentation\Api\Helper $helper */
                    $helper = $container[Helper::class];
                    return $helper->responseFailure($response, 404, 'Not Found');
                },
                function (ServerRequestInterface $request, ResponseInterface $response) use ($container): ResponseInterface {
                    /** @var \Search2d\Presentation\Api\Helper $helper */
                    $helper = $container[Helper::class];
                    return $helper->responseFailure($response, 405, 'Method Not Allowed');
                }
            );
        };
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
     * @param \FastRoute\RouteCollector $r
     * @return void
     */
    private function routeDefinition(RouteCollector $r): void
    {
        $r->addRoute('POST', '/query/img', QueryImgAction::class);
        $r->addRoute('POST', '/query/url', QueryUrlAction::class);
        $r->addRoute('GET', '/search/{sha1:[a-zA-Z0-9]{40}}', SearchAction::class);
    }
}