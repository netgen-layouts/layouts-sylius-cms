<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\Item\Section;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Section\Item;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    private SectionInterface $section;

    private Item $item;

    protected function setUp(): void
    {
        $this->section = new Section(42, 'blog');
        $this->section->setCurrentLocale('en');
        $this->section->setFallbackLocale('en');
        $this->section->setName('Blog posts');

        $this->item = new Item($this->section);
    }

    public function testGetValue(): void
    {
        self::assertSame(42, $this->item->value);
    }

    public function testGetName(): void
    {
        self::assertSame('Blog posts', $this->item->name);
    }

    public function testGetSection(): void
    {
        self::assertSame($this->section, $this->item->section);
    }
}
