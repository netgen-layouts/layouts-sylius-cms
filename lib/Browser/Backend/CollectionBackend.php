<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Backend;

use Netgen\ContentBrowser\Backend\BackendInterface;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Backend\SearchResult;
use Netgen\ContentBrowser\Backend\SearchResultInterface;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\ContentBrowser\Item\LocationInterface;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Collection\Item;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;

use function max;
use function sprintf;

final class CollectionBackend implements BackendInterface
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
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
        /** @var \Sylius\CmsPlugin\Entity\CollectionInterface $collection */
        $collection = $this->collectionRepository->find($value) ??
            throw new NotFoundException(
                sprintf(
                    'Item with value "%s" not found.',
                    $value,
                ),
            );

        return $this->buildItem($collection);
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
        $paginator = $this->collectionRepository->createListPaginator();

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
        $paginator = $this->collectionRepository->createListPaginator();

        return $paginator->getNbResults();
    }

    public function searchItems(SearchQuery $searchQuery): SearchResultInterface
    {
        $paginator = $this->collectionRepository->createSearchPaginator(
            $searchQuery->searchText,
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
        $paginator = $this->collectionRepository->createSearchPaginator(
            $searchQuery->searchText,
        );

        return $paginator->getNbResults();
    }

    /**
     * Builds the item from provided collection.
     */
    private function buildItem(CollectionInterface $collection): Item
    {
        return new Item($collection);
    }

    /**
     * Builds the items from provided collections.
     *
     * @param iterable<\Sylius\CmsPlugin\Entity\CollectionInterface> $collections
     *
     * @return iterable<\Netgen\Layouts\Sylius\Cms\Browser\Item\Collection\Item>
     */
    private function buildItems(iterable $collections): iterable
    {
        foreach ($collections as $collection) {
            yield $this->buildItem($collection);
        }
    }
}
