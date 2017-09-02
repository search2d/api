<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

use Cake\Chronos\Chronos;
use Generator;
use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\Illust;
use Search2d\Domain\Pixiv\IllustPage;
use Search2d\Domain\Pixiv\IllustPageCollection;
use Search2d\Domain\Pixiv\Ranking;
use Search2d\Domain\Pixiv\RankingDate;
use Search2d\Domain\Pixiv\RankingIllust;
use Search2d\Domain\Pixiv\RankingIllustCollection;
use Search2d\Domain\Pixiv\RankingMode;
use Search2d\Domain\Pixiv\RemoteRepository;
use Search2d\Domain\Search\Image;

class ApiRemoteRepository implements RemoteRepository
{
    /** @var \Search2d\Infrastructure\Domain\Pixiv\ApiClient */
    private $client;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Search2d\Infrastructure\Domain\Pixiv\ApiClient $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ApiClient $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param int $illustId
     * @return \Search2d\Domain\Pixiv\Illust
     */
    public function getIllust(int $illustId): Illust
    {
        $illust = $this->client->getIllustDetail($illustId)->illust;

        $pages = [];
        if ($illust->page_count === 1) {
            $pages[] = new IllustPage(0, $illust->meta_single_page->original_image_url);
        } else {
            foreach ($illust->meta_pages as $offset => $metaPage) {
                $pages[] = new IllustPage($offset, $metaPage->image_urls->original);
            }
        }

        return new Illust(
            $illust->id,
            sprintf('https://www.pixiv.net/member_illust.php?mode=medium&illust_id=%d', $illust->id),
            $illust->title,
            new IllustPageCollection($pages),
            Chronos::now('UTC')
        );
    }

    /**
     * @param string $url
     * @return \Search2d\Domain\Search\Image
     */
    public function getImage(string $url): Image
    {
        $response = $this->client->requestImg('GET', $url);

        return Image::create($response->getBody()->getContents());
    }

    /**
     * @param \Search2d\Domain\Pixiv\RankingMode $mode
     * @param \Search2d\Domain\Pixiv\RankingDate $date
     * @return \Search2d\Domain\Pixiv\Ranking
     */
    public function getRanking(RankingMode $mode, RankingDate $date): Ranking
    {
        $illusts = [];
        /** @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking $illustRanking */
        foreach ($this->getIllustRanking($mode->value, $date->value->format('Y-m-d')) as $illustRanking) {
            $illusts = array_merge($illusts, $illustRanking->illusts);
        }

        $rankingIllusts = [];
        foreach ($illusts as $offset => $illust) {
            $rankingIllusts[] = new RankingIllust($offset, $illust->id);
        }

        return new Ranking($mode, $date, new RankingIllustCollection($rankingIllusts));
    }

    /**
     * @param string $mode
     * @param string $date
     * @return \Generator
     */
    private function getIllustRanking(string $mode, string $date): Generator
    {
        /** @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking $current */
        $current = null;

        // Pixivのバグや仕様変更で無限ループしないように回数を制限する
        for ($i = 0; $i < 50; $i++) {
            if (!$current) {
                $current = $this->client->getIllustRanking($mode, $date);
            } else {
                $current = $this->client->getIllustRankingNext($current->next_url);
            }

            yield $current;

            if ($current->next_url === null) {
                return;
            }
        }

        throw new \RuntimeException('ランキング取得のループ回数制限を超えた');
    }
}