<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Browser\Backend;

use ArrayIterator;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\Cms\Browser\Backend\MediaBackend;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Media\Item;
use Netgen\Layouts\Sylius\Cms\Repository\MediaRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Media;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;

#[CoversClass(MediaBackend::class)]
final class MediaBackendTest extends TestCase
{
    private Stub&MediaRepositoryInterface $mediaRepositoryStub;

    private MediaBackend $backend;

    protected function setUp(): void
    {
        $this->mediaRepositoryStub = self::createStub(MediaRepositoryInterface::class);

        $localeContextStub = self::createStub(LocaleContextInterface::class);
        $localeContextStub
            ->method('getLocaleCode')
            ->willReturn('en');

        $this->backend = new MediaBackend(
            $this->mediaRepositoryStub,
            $localeContextStub,
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
        $this->mediaRepositoryStub
            ->method('find')
            ->willReturn(new Media(1, 'banner'));

        $item = $this->backend->loadItem(1);

        self::assertSame(1, $item->value);
    }

    public function testLoadItemThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Item with value "1" not found.');

        $this->mediaRepositoryStub
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
            ->willReturn(new ArrayIterator([new Media(42, 'banner'), new Media(43, 'logo')]));

        $this->mediaRepositoryStub
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

    public function testGetSubItemsCount(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);
        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(2);

        $this->mediaRepositoryStub
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
            ->willReturn(new ArrayIterator([new Media(42, 'banner'), new Media(43, 'logo')]));

        $this->mediaRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $searchResult = [...$this->backend->searchItems(new SearchQuery('test'))->results];

        self::assertCount(2, $searchResult);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult);
    }

    public function testSearchItemsCount(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);
        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(2);

        $this->mediaRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $count = $this->backend->searchItemsCount(new SearchQuery('test'));

        self::assertSame(2, $count);
    }
}
