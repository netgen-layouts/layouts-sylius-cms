<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\Backend;

use BitBag\SyliusCmsPlugin\Entity\BlockInterface;
use Netgen\ContentBrowser\Backend\BackendInterface;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Backend\SearchResult;
use Netgen\ContentBrowser\Backend\SearchResultInterface;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\ContentBrowser\Item\LocationInterface;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Block\Item;
use Netgen\Layouts\Sylius\BitBag\Repository\BlockRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

use function max;
use function sprintf;

final class BlockBackend implements BackendInterface
{
    public function __construct(
        private BlockRepositoryInterface $blockRepository,
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
        /** @var \BitBag\SyliusCmsPlugin\Entity\BlockInterface $block */
        $block = $this->blockRepository->find($value) ??
            throw new NotFoundException(
                sprintf(
                    'Item with value "%s" not found.',
                    $value,
                ),
            );

        return $this->buildItem($block);
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
        $paginator = $this->blockRepository->createListPaginator(
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
        $paginator = $this->blockRepository->createListPaginator(
            $this->localeContext->getLocaleCode(),
        );

        return $paginator->getNbResults();
    }

    public function searchItems(SearchQuery $searchQuery): SearchResultInterface
    {
        $paginator = $this->blockRepository->createSearchPaginator(
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
        $paginator = $this->blockRepository->createSearchPaginator(
            $searchQuery->searchText,
            $this->localeContext->getLocaleCode(),
        );

        return $paginator->getNbResults();
    }

    /**
     * Builds the item from provided block.
     */
    private function buildItem(BlockInterface $block): Item
    {
        return new Item($block);
    }

    /**
     * Builds the items from provided blocks.
     *
     * @param iterable<\BitBag\SyliusCmsPlugin\Entity\BlockInterface> $blocks
     *
     * @return iterable<\Netgen\Layouts\Sylius\BitBag\Browser\Item\Block\Item>
     */
    private function buildItems(iterable $blocks): iterable
    {
        foreach ($blocks as $block) {
            yield $this->buildItem($block);
        }
    }
}
