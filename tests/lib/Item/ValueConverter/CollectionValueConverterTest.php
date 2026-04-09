<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Cms\Item\ValueConverter\CollectionValueConverter;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Collection as CollectionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\Collection;
use Sylius\CmsPlugin\Entity\Page;

#[CoversClass(CollectionValueConverter::class)]
final class CollectionValueConverterTest extends TestCase
{
    private CollectionValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new CollectionValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Collection()));
        self::assertFalse($this->valueConverter->supports(new Page()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_cms_collection',
            $this->valueConverter->getValueType(
                new Collection(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new CollectionStub(42, 'blog', 'Blog'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new CollectionStub(42, 'blog', 'Blog'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'Blog',
            $this->valueConverter->getName(
                new CollectionStub(42, 'blog', 'Blog'),
            ),
        );
    }

    public function testGetObject(): void
    {
        $collection = new CollectionStub(42, 'blog', 'Blog');

        self::assertSame($collection, $this->valueConverter->getObject($collection));
    }
}
