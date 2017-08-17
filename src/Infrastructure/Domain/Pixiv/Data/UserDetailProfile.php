<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class UserDetailProfile
{
    /**
     * @var string|null
     * @rquired
     */
    public $webpage;

    /**
     * @var string
     * @rquired
     */
    public $gender;

    /**
     * @var string
     * @rquired
     */
    public $birth;

    /**
     * @var string
     * @rquired
     */
    public $birth_day;

    /**
     * @var int
     * @rquired
     */
    public $birth_year;

    /**
     * @var string
     * @rquired
     */
    public $region;

    /**
     * @var int
     * @rquired
     */
    public $address_id;

    /**
     * @var string
     * @rquired
     */
    public $country_code;

    /**
     * @var string
     * @rquired
     */
    public $job;

    /**
     * @var int
     * @rquired
     */
    public $job_id;

    /**
     * @var int
     * @rquired
     */
    public $total_follow_users;

    /**
     * @var int
     * @rquired
     */
    public $total_follower;

    /**
     * @var int
     * @rquired
     */
    public $total_mypixiv_users;

    /**
     * @var int
     * @rquired
     */
    public $total_illusts;

    /**
     * @var int
     * @rquired
     */
    public $total_manga;

    /**
     * @var int
     * @rquired
     */
    public $total_novels;

    /**
     * @var int
     * @rquired
     */
    public $total_illust_bookmarks_public;

    /**
     * @var string|null
     * @rquired
     */
    public $background_image_url;

    /**
     * @var string
     * @rquired
     */
    public $twitter_account;

    /**
     * @var string|null
     * @rquired
     */
    public $twitter_url;

    /**
     * @var string|null
     * @rquired
     */
    public $pawoo_url;

    /**
     * @var bool
     * @rquired
     */
    public $is_premium;

    /**
     * @var bool
     * @rquired
     */
    public $is_using_custom_profile_image;
}