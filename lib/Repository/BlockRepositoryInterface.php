<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface as BaseBlockRepositoryInterface;

interface BlockRepositoryInterface extends BaseBlockRepositoryInterface
{
    /**
     * Creates a query builder for listing blocks with translations.
     */
    public function createListQueryBuilder(string $localeCode): QueryBuilder;

    /**
     * Creates a paginator which is used to list blocks.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Block>
     */
    public function createListPaginator(string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for blocks.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Block>
     */
    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface;
}
