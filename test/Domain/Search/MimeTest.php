<?php
declare(strict_types=1);

namespace Search2d\Test\Domain\Search;

use Search2d\Domain\Search\Mime;
use Search2d\Domain\Search\MimeDetectionException;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Domain\Search\Mime
 */
class MimeTest extends TestCase
{
    /**
     * @return void
     */
    public function testDetectJpeg(): void
    {
        $mime = Mime::detect(file_get_contents(__DIR__ . '/fixture/600x400.jpg'));
        $this->assertSame('image/jpeg', $mime->value);
    }

    /**
     * @return void
     */
    public function testDetectPng(): void
    {
        $mime = Mime::detect(file_get_contents(__DIR__ . '/fixture/600x400.png'));
        $this->assertSame('image/png', $mime->value);
    }

    /**
     * @return void
     */
    public function testDetectGif(): void
    {
        $mime = Mime::detect(file_get_contents(__DIR__ . '/fixture/600x400.gif'));
        $this->assertSame('image/gif', $mime->value);
    }

    /**
     * @return void
     */
    public function testDetectBmp(): void
    {
        $this->expectException(MimeDetectionException::class);
        Mime::detect(file_get_contents(__DIR__ . '/fixture/600x400.bmp'));
    }

    /**
     * @return void
     */
    public function testDetectTiff(): void
    {
        $this->expectException(MimeDetectionException::class);
        Mime::detect(file_get_contents(__DIR__ . '/fixture/600x400.tiff'));
    }
}