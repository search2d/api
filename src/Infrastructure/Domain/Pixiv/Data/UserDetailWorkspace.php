<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class UserDetailWorkspace
{
    /**
     * @var string
     * @required
     */
    public $pc;

    /**
     * @var string
     * @required
     */
    public $monitor;

    /**
     * @var string
     * @required
     */
    public $tool;

    /**
     * @var string
     * @required
     */
    public $scanner;

    /**
     * @var string
     * @required
     */
    public $tablet;

    /**
     * @var string
     * @required
     */
    public $mouse;

    /**
     * @var string
     * @required
     */
    public $printer;

    /**
     * @var string
     * @required
     */
    public $desktop;

    /**
     * @var string
     * @required
     */
    public $music;

    /**
     * @var string
     * @required
     */
    public $desk;

    /**
     * @var string
     * @required
     */
    public $chair;

    /**
     * @var string
     * @required
     */
    public $comment;

    /**
     * @var string|null
     * @required
     */
    public $workspace_image_url;
}