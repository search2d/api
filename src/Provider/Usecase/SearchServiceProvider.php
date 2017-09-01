<?php
declare(strict_types=1);

namespace Search2d\Provider\Usecase;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Search2d\Domain\Search\ImageFetcher;
use Search2d\Domain\Search\IndexedImageRepository;
use Search2d\Domain\Search\IndexedImageStorage;
use Search2d\Domain\Search\NnsRepository;
use Search2d\Domain\Search\QueriedImageRepository;
use Search2d\Domain\Search\QueriedImageStorage;
use Search2d\Infrastructure\Usecase\CommandMapper;
use Search2d\Usecase\Search\IndexCommand;
use Search2d\Usecase\Search\IndexHandler;
use Search2d\Usecase\Search\QueryImgCommand;
use Search2d\Usecase\Search\QueryImgHandler;
use Search2d\Usecase\Search\QueryUrlCommand;
use Search2d\Usecase\Search\QueryUrlHandler;
use Search2d\Usecase\Search\SearchCommand;
use Search2d\Usecase\Search\SearchHandler;

class SearchServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[IndexHandler::class] = function (Container $container) {
            return new IndexHandler(
                $container[IndexedImageRepository::class],
                $container[IndexedImageStorage::class],
                $container[NnsRepository::class]
            );
        };

        $container[QueryImgHandler::class] = function (Container $container) {
            return new QueryImgHandler(
                $container[QueriedImageRepository::class],
                $container[QueriedImageStorage::class]
            );
        };

        $container[QueryUrlHandler::class] = function (Container $container) {
            return new QueryUrlHandler(
                $container[ImageFetcher::class],
                $container[QueryImgHandler::class]
            );
        };

        $container[SearchHandler::class] = function (Container $container) {
            return new SearchHandler(
                $container[QueriedImageRepository::class],
                $container[IndexedImageRepository::class],
                $container[NnsRepository::class],
                $container[LoggerInterface::class]
            );
        };

        $container->extend(CommandMapper::class, function (CommandMapper $mapper, Container $container) {
            $mapper->addMapping([
                IndexCommand::class => IndexHandler::class,
                QueryImgCommand::class => QueryImgHandler::class,
                QueryUrlCommand::class => QueryUrlHandler::class,
                SearchCommand::class => SearchHandler::class,
            ]);
            return $mapper;
        });
    }
}