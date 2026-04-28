<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\Cms\Item\ValueLoader\CollectionValueLoader;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(CollectionValueLoader::class)]
final class CollectionValueLoaderTest extends TestCase
{
    private Stub&CollectionRepositoryInterface $collectionRepositoryStub;

    private CollectionValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->collectionRepositoryStub = self::createStub(CollectionRepositoryInterface::class);
        $this->valueLoader = new CollectionValueLoader($this->collectionRepositoryStub);
    }

    public function testLoad(): void
    {
        $collection = new Collection(42, 'blog', 'Blog');

        $this->collectionRepositoryStub
            ->method('find')
            ->willReturn($collection);

        self::assertSame($collection, $this->valueLoader->load(42));
    }

    public function testLoadWithNoCollection(): void
    {
        $this->collectionRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->collectionRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $collection = new Collection(42, 'blog', 'Blog');

        $this->collectionRepositoryStub
            ->method('find')
            ->willReturn($collection);

        self::assertSame($collection, $this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithNoCollection(): void
    {
        $this->collectionRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->collectionRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }
}
