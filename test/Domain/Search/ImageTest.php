<?php
declare(strict_types=1);

namespace Search2d\Test\Domain\Search;

use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\ImageValidationException;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Domain\Search\Image
 */
class ImageTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateJpeg(): void
    {
        $data = file_get_contents(__DIR__ . '/fixture/600x400.jpg');

        $image = Image::create($data);

        $this->assertSame($data, $image->getData());
        $this->assertSame('image/jpeg', $image->getMime()->value);
        $this->assertSame(600, $image->getWidth());
        $this->assertSame(400, $image->getHeight());
        $this->assertSame('414bbce5792315509f2136a8dd4c2c8c8ab76ea3', $image->getSha1()->value);
        $this->assertSame(11747, $image->getSize());
    }

    /**
     * @return void
     */
    public function testCreatePng(): void
    {
        $data = file_get_contents(__DIR__ . '/fixture/600x400.png');

        $image = Image::create($data);

        $this->assertSame($data, $image->getData());
        $this->assertSame('image/png', $image->getMime()->value);
        $this->assertSame(600, $image->getWidth());
        $this->assertSame(400, $image->getHeight());
        $this->assertSame('8cf6c65fa79d3b4d7858c620e0e25a112b0e8a89', $image->getSha1()->value);
        $this->assertSame(1780, $image->getSize());
    }

    /**
     * @return void
     */
    public function testCreateGif(): void
    {
        $data = file_get_contents(__DIR__ . '/fixture/600x400.gif');

        $image = Image::create($data);

        $this->assertSame($data, $image->getData());
        $this->assertSame('image/gif', $image->getMime()->value);
        $this->assertSame(600, $image->getWidth());
        $this->assertSame(400, $image->getHeight());
        $this->assertSame('d0b78aab6234f4b3817eeee23083a8042d1f05eb', $image->getSha1()->value);
        $this->assertSame(2734, $image->getSize());
    }

    /**
     * @return void
     */
    public function testCreateBmp(): void
    {
        $this->expectException(ImageValidationException::class);
        Image::create(file_get_contents(__DIR__ . '/fixture/600x400.bmp'));
    }

    /**
     * @return void
     */
    public function testCreateTiff(): void
    {
        $this->expectException(ImageValidationException::class);
        Image::create(file_get_contents(__DIR__ . '/fixture/600x400.tiff'));
    }
}