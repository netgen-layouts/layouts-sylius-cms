<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\Backend;

use ArrayIterator;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\BitBag\Browser\Backend\PageBackend;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Page\Item;
use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Page;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;

#[CoversClass(PageBackend::class)]
final class PageBackendTest extends TestCase
{
    private Stub&PageRepositoryInterface $pageRepositoryStub;

    private PageBackend $backend;

    protected function setUp(): void
    {
        $this->pageRepositoryStub = self::createStub(PageRepositoryInterface::class);
        $localeContextStub = self::createStub(LocaleContextInterface::class);

        $localeContextStub
            ->method('getLocaleCode')
            ->willReturn('en');

        $this->backend = new PageBackend(
            $this->pageRepositoryStub,
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
        $this->pageRepositoryStub
            ->method('find')
            ->with(self::identicalTo(1))
            ->willReturn(new Page(1, 'contact-us'));

        $item = $this->backend->loadItem(1);

        self::assertSame(1, $item->value);
    }

    public function testLoadItemThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Item with value "1" not found.');

        $this->pageRepositoryStub
            ->method('find')
            ->with(self::identicalTo(1))
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
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(new ArrayIterator([new Page(42, 'about-us'), new Page(43, 'contact-us')]));

        $this->pageRepositoryStub
            ->method('createListPaginator')
            ->with(self::identicalTo('en'))
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
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(new ArrayIterator([new Page(42, 'about-us'), new Page(43, 'contact-us')]));

        $this->pageRepositoryStub
            ->method('createListPaginator')
            ->with(self::identicalTo('en'))
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

        $this->pageRepositoryStub
            ->method('createListPaginator')
            ->with(self::identicalTo('en'))
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
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(new ArrayIterator([new Page(42, 'about-us'), new Page(43, 'contact-us')]));

        $this->pageRepositoryStub
            ->method('createSearchPaginator')
            ->with(self::identicalTo('test'), self::identicalTo('en'))
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
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(new ArrayIterator([new Page(42, 'about-us'), new Page(43, 'contact-us')]));

        $this->pageRepositoryStub
            ->method('createSearchPaginator')
            ->with(self::identicalTo('test'), self::identicalTo('en'))
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

        $this->pageRepositoryStub
            ->method('createSearchPaginator')
            ->with(self::identicalTo('test'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $count = $this->backend->searchItemsCount(new SearchQuery('test'));

        self::assertSame(2, $count);
    }
}
