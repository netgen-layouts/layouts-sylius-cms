<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\ContentBrowser\Backend;

use ArrayIterator;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\Cms\Browser\Backend\CollectionBackend;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Collection\Item;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Collection;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(CollectionBackend::class)]
final class CollectionBackendTest extends TestCase
{
    private Stub&CollectionRepositoryInterface $collectionRepositoryStub;

    private CollectionBackend $backend;

    protected function setUp(): void
    {
        $this->collectionRepositoryStub = self::createStub(CollectionRepositoryInterface::class);

        $this->backend = new CollectionBackend(
            $this->collectionRepositoryStub,
        );
    }

    public function testGetSections(): void
    {
        $locations = $this->backend->getSections();

        self::assertCount(1, $locations);
        self::assertContainsOnlyInstancesOf(RootLocation::class, $locations);
    }

    public function testLoadItem(): void
    {
        $this->collectionRepositoryStub
            ->method('find')
            ->willReturn(new Collection(1, 'blog'));

        $item = $this->backend->loadItem(1);

        self::assertSame(1, $item->value);
    }

    public function testLoadItemThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Item with value "1" not found.');

        $this->collectionRepositoryStub
            ->method('find')
            ->willReturn(null);

        $this->backend->loadItem(1);
    }

    public function testGetSubLocations(): void
    {
        $locations = $this->backend->getSubLocations(new RootLocation());

        self::assertCount(0, $locations);
    }

    public function testGetSubLocationsCount(): void
    {
        $count = $this->backend->getSubLocationsCount(new RootLocation());

        self::assertSame(0, $count);
    }

    public function testGetSubItems(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);
        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(new ArrayIterator([new Collection(42, 'blog'), new Collection(43, 'news')]));

        $this->collectionRepositoryStub
            ->method('createListPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $items = [
            ...$this->backend->getSubItems(
                new RootLocation(),
            ),
        ];

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testGetSubItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(new ArrayIterator([new Collection(42, 'blog'), new Collection(43, 'news')]));

        $this->collectionRepositoryStub
            ->method('createListPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $items = [
            ...$this->backend->getSubItems(
                new RootLocation(),
                8,
                2,
            ),
        ];

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testGetSubItemsCount(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);
        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(2);

        $this->collectionRepositoryStub
            ->method('createListPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $count = $this->backend->getSubItemsCount(
            new RootLocation(),
        );

        self::assertSame(2, $count);
    }

    public function testSearchItems(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);
        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(new ArrayIterator([new Collection(42, 'blog'), new Collection(43, 'news')]));

        $this->collectionRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $searchResult = [...$this->backend->searchItems(new SearchQuery('test'))->results];

        self::assertCount(2, $searchResult);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult);
    }

    public function testSearchItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(new ArrayIterator([new Collection(42, 'blog'), new Collection(43, 'news')]));

        $this->collectionRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $searchQuery = new SearchQuery('test');
        $searchQuery->offset = 8;
        $searchQuery->limit = 2;

        $searchResult = [...$this->backend->searchItems($searchQuery)->results];

        self::assertCount(2, $searchResult);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult);
    }

    public function testSearchItemsCount(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);
        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(2);

        $this->collectionRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $count = $this->backend->searchItemsCount(new SearchQuery('test'));

        self::assertSame(2, $count);
    }
}
