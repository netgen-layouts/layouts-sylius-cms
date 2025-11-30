<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\Backend;

use ArrayIterator;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\BitBag\Browser\Backend\FrequentlyAskedQuestionBackend;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\FrequentlyAskedQuestion\Item;
use Netgen\Layouts\Sylius\BitBag\Repository\FrequentlyAskedQuestionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\FrequentlyAskedQuestion;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;

#[CoversClass(FrequentlyAskedQuestionBackend::class)]
final class FrequentlyAskedQuestionBackendTest extends TestCase
{
    private MockObject&FrequentlyAskedQuestionRepositoryInterface $frequentlyAskedQuestionRepositoryMock;

    private FrequentlyAskedQuestionBackend $backend;

    protected function setUp(): void
    {
        $this->frequentlyAskedQuestionRepositoryMock = $this->createMock(FrequentlyAskedQuestionRepositoryInterface::class);
        $localeContextMock = $this->createMock(LocaleContextInterface::class);

        $localeContextMock
            ->method('getLocaleCode')
            ->willReturn('en');

        $this->backend = new FrequentlyAskedQuestionBackend(
            $this->frequentlyAskedQuestionRepositoryMock,
            $localeContextMock,
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
        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(1))
            ->willReturn(new FrequentlyAskedQuestion(1, 'TEST_QUESTION'));

        $item = $this->backend->loadItem(1);

        self::assertSame(1, $item->value);
    }

    public function testLoadItemThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Item with value "1" not found.');

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
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
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);
        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(new ArrayIterator([
                new FrequentlyAskedQuestion(42, 'TEST_QUESTION'),
                new FrequentlyAskedQuestion(43, 'TEST_QUESTION_2'),
            ]));

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('createListPaginator')
            ->with(self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

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
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(new ArrayIterator([
                new FrequentlyAskedQuestion(42, 'TEST_QUESTION'),
                new FrequentlyAskedQuestion(43, 'TEST_QUESTION_2'),
            ]));

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('createListPaginator')
            ->with(self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

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
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);
        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(2);

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('createListPaginator')
            ->with(self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $count = $this->backend->getSubItemsCount(
            new RootLocation(),
        );

        self::assertSame(2, $count);
    }

    public function testSearchItems(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);
        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(new ArrayIterator([
                new FrequentlyAskedQuestion(42, 'TEST_QUESTION'),
                new FrequentlyAskedQuestion(43, 'TEST_QUESTION_2'),
            ]));

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('createSearchPaginator')
            ->with(self::identicalTo('test'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $searchResult = [...$this->backend->searchItems(new SearchQuery('test'))->results];

        self::assertCount(2, $searchResult);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult);
    }

    public function testSearchItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(new ArrayIterator([
                new FrequentlyAskedQuestion(42, 'TEST_QUESTION'),
                new FrequentlyAskedQuestion(43, 'TEST_QUESTION_2'),
            ]));

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('createSearchPaginator')
            ->with(self::identicalTo('test'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $searchQuery = new SearchQuery('test');
        $searchQuery->offset = 8;
        $searchQuery->limit = 2;

        $searchResult = [...$this->backend->searchItems($searchQuery)->results];

        self::assertCount(2, $searchResult);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult);
    }

    public function testSearchItemsCount(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);
        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(2);

        $this->frequentlyAskedQuestionRepositoryMock
            ->expects($this->once())
            ->method('createSearchPaginator')
            ->with(self::identicalTo('test'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $count = $this->backend->searchItemsCount(new SearchQuery('test'));

        self::assertSame(2, $count);
    }
}
