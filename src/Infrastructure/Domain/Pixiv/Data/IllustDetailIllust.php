<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class IllustDetailIllust
{
    /**
     * @var int
     * @required
     */
    public $id;

    /**
     * @var string
     * @required
     */
    public $title;

    /**
     * @var string
     * @required
     */
    public $type;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetailIllustImageUrls
     * @required
     */
    public $image_urls;

    /**
     * @var string
     * @required
     */
    public $caption;

    /**
     * @var int
     * @required
     */
    public $restrict;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetailIllustUser
     * @required
     */
    public $user;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetailIllustTag[]
     * @required
     */
    public $tags;

    /**
     * @var string[]
     * @required
     */
    public $tools;

    /**
     * @var string
     * @required
     */
    public $create_date;

    /**
     * @var int
     * @required
     */
    public $page_count;

    /**
     * @var int
     * @required
     */
    public $width;

    /**
     * @var int
     * @required
     */
    public $height;

    /**
     * @var int
     * @required
     */
    public $sanity_level;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetailIllustMetaSinglePage
     * @required
     */
    public $meta_single_page;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetailIllustMetaPage[]
     * @required
     */
    public $meta_pages;

    /**
     * @var int
     * @required
     */
    public $total_view;

    /**
     * @var int
     * @required
     */
    public $total_bookmarks;

    /**
     * @var bool
     * @required
     */
    public $is_bookmarked;

    /**
     * @var bool
     * @required
     */
    public $visible;

    /**
     * @var bool
     * @required
     */
    public $is_muted;

    /**
     * @var int
     * @required
     */
    public $total_comments;
}