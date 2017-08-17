<?php
declare(strict_types=1);

namespace Search2d\Provider\Domain;

use Aws\S3\S3Client;
use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Search2d\Domain\Search\ImageFetcher;
use Search2d\Domain\Search\IndexedImageRepository;
use Search2d\Domain\Search\IndexedImageStorage;
use Search2d\Domain\Search\NnsRepository;
use Search2d\Domain\Search\QueriedImageRepository;
use Search2d\Domain\Search\QueriedImageStorage;
use Search2d\Infrastructure\Domain\Search\DbalIndexedImageRepository;
use Search2d\Infrastructure\Domain\Search\DbalQueriedImageRepository;
use Search2d\Infrastructure\Domain\Search\GuzzleImageFetcher;
use Search2d\Infrastructure\Domain\Search\GuzzleNnsRepository;
use Search2d\Infrastructure\Domain\Search\IpResolver;
use Search2d\Infrastructure\Domain\Search\S3ImageStorage;
use Search2d\Infrastructure\Domain\Search\UrlValidator;

class SearchServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[IndexedImageRepository::class] = function (Container $container) {
            return new DbalIndexedImageRepository($container[Connection::class]);
        };

        $container[QueriedImageRepository::class] = function (Container $container) {
            return new DbalQueriedImageRepository($container[Connection::class]);
        };

        $container['search.s3_client'] = function (Container $container) {
            $search = $container['config']->search;
            return new S3Client([
                'version' => '2006-03-01',
                'region' => $search->s3->region,
            ]);
        };

        $container[IndexedImageStorage::class] = function (Container $container) {
            $search = $container['config']->search;
            return new S3ImageStorage(
                $container['search.s3_client'],
                $search->s3->indexed_image_bucket,
                $search->s3->indexed_image_base_url
            );
        };

        $container[QueriedImageStorage::class] = function (Container $container) {
            $search = $container['config']->search;
            return new S3ImageStorage(
                $container['search.s3_client'],
                $search->s3->queried_image_bucket,
                $search->s3->queried_image_base_url
            );
        };

        $container[NnsRepository::class] = function (Container $container) {
            $search = $container['config']->search;
            return new GuzzleNnsRepository(
                new Client([
                    'base_uri' => $search->nns->base_uri,
                    RequestOptions::TIMEOUT => $search->nns->timeout,
                    RequestOptions::CONNECT_TIMEOUT => $search->nns->connection_timeout,
                ])
            );
        };

        $container[ImageFetcher::class] = function (Container $container) {
            $search = $container['config']->search;
            return new GuzzleImageFetcher(
                new Client([
                    RequestOptions::TIMEOUT => $search->fetcher->timeout,
                    RequestOptions::CONNECT_TIMEOUT => $search->fetcher->connection_timeout,
                ]),
                new UrlValidator(new IpResolver())
            );
        };
    }
}