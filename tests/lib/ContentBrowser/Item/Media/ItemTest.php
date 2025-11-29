<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\Item\Media;

use BitBag\SyliusCmsPlugin\Entity\MediaInterface;
use Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Media\Item;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Media;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    private MediaInterface $media;

    private Item $item;

    protected function setUp(): void
    {
        $this->media = new Media(42, 'logo');
        $this->media->setCurrentLocale('en');
        $this->media->setFallbackLocale('en');
        $this->media->setName('Logo');

        $this->item = new Item($this->media);
    }

    public function testGetValue(): void
    {
        self::assertSame(42, $this->item->value);
    }

    public function testGetName(): void
    {
        self::assertSame('Logo', $this->item->name);
    }

    public function testGetNameWithEmptyName(): void
    {
        $media = new Media(42, 'logo');
        $media->setCurrentLocale('en');
        $media->setFallbackLocale('en');
        $item = new Item($media);

        self::assertSame('logo', $item->name);
    }

    public function testGetNameWithEmptyNameAndCode(): void
    {
        $media = new Media(42, '');
        $media->setCurrentLocale('en');
        $media->setFallbackLocale('en');
        $item = new Item($media);

        self::assertSame('', $item->name);
    }

    public function testGetMedia(): void
    {
        self::assertSame($this->media, $this->item->media);
    }
}
