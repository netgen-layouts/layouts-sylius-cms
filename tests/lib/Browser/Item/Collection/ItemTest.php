<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\ContentBrowser\Item\Collection;

use Netgen\Layouts\Sylius\Cms\Browser\Item\Collection\Item;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    private CollectionInterface $collection;

    private Item $item;

    protected function setUp(): void
    {
        $this->collection = new Collection(42, 'blog');
        $this->collection->setName('Blog posts');

        $this->item = new Item($this->collection);
    }

    public function testGetValue(): void
    {
        self::assertSame(42, $this->item->value);
    }

    public function testGetName(): void
    {
        self::assertSame('Blog posts', $this->item->name);
    }

    public function testGetCollection(): void
    {
        self::assertSame($this->collection, $this->item->collection);
    }
}
