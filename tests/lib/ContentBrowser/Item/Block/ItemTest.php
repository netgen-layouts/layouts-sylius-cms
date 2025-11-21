<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\Item\Block;

use BitBag\SyliusCmsPlugin\Entity\BlockInterface;
use Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Block\Item;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Block;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    private BlockInterface $block;

    private Item $item;

    protected function setUp(): void
    {
        $this->block = new Block(42, 'header');
        $this->block->setCurrentLocale('en');
        $this->block->setFallbackLocale('en');
        $this->block->setName('Header');

        $this->item = new Item($this->block);
    }

    public function testGetValue(): void
    {
        self::assertSame(42, $this->item->value);
    }

    public function testGetName(): void
    {
        self::assertSame('Header', $this->item->name);
    }

    public function testGetBlock(): void
    {
        self::assertSame($this->block, $this->item->block);
    }
}
