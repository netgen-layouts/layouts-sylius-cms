<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\Backend;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use Netgen\ContentBrowser\Backend\BackendInterface;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Backend\SearchResult;
use Netgen\ContentBrowser\Backend\SearchResultInterface;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\ContentBrowser\Item\LocationInterface;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Page\Item;
use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

use function max;
use function sprintf;

final class PageBackend implements BackendInterface
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private LocaleContextInterface $localeContext,
    ) {}

    public function getSections(): iterable
    {
        return [new RootLocation()];
    }

    public function loadLocation(int|string $id): RootLocation
    {
        return new RootLocation();
    }

    public function loadItem(int|string $value): Item
    {
        $page = $this->pageRepository->find($value) ??
            throw new NotFoundException(
                sprintf(
                    'Item with value "%s" not found.',
                    $value,
                ),
            );

        return $this->buildItem($page);
    }

    public function getSubLocations(LocationInterface $location): iterable
    {
        return [];
    }

    public function getSubLocationsCount(LocationInterface $location): int
    {
        return 0;
    }

    public function getSubItems(LocationInterface $location, int $offset = 0, int $limit = 25): iterable
    {
        $paginator = $this->pageRepository->createListPaginator(
            $this->localeContext->getLocaleCode(),
        );

        $limit = max(0, $limit);
        $offset = max(0, $offset);

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage((int) ($offset / $limit) + 1);

        return $this->buildItems(
            $paginator->getAdapter()->getSlice($offset, $limit),
        );
    }

    public function getSubItemsCount(LocationInterface $location): int
    {
        $paginator = $this->pageRepository->createListPaginator(
            $this->localeContext->getLocaleCode(),
        );

        return $paginator->getNbResults();
    }

    public function searchItems(SearchQuery $searchQuery): SearchResultInterface
    {
        $paginator = $this->pageRepository->createSearchPaginator(
            $searchQuery->searchText,
            $this->localeContext->getLocaleCode(),
        );

        $paginator->setMaxPerPage($searchQuery->limit);
        $paginator->setCurrentPage((int) ($searchQuery->offset / $searchQuery->limit) + 1);

        return new SearchResult(
            $this->buildItems(
                $paginator->getCurrentPageResults(),
            ),
        );
    }

    public function searchItemsCount(SearchQuery $searchQuery): int
    {
        $paginator = $this->pageRepository->createSearchPaginator(
            $searchQuery->searchText,
            $this->localeContext->getLocaleCode(),
        );

        return $paginator->getNbResults();
    }

    /**
     * Builds the item from provided page.
     */
    private function buildItem(PageInterface $page): Item
    {
        return new Item($page);
    }

    /**
     * Builds the items from provided pages.
     *
     * @param iterable<\BitBag\SyliusCmsPlugin\Entity\PageInterface> $pages
     *
     * @return iterable<\Netgen\Layouts\Sylius\BitBag\Browser\Item\Page\Item>
     */
    private function buildItems(iterable $pages): iterable
    {
        foreach ($pages as $page) {
            yield $this->buildItem($page);
        }
    }
}
