<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface as BaseMediaRepositoryInterface;

interface MediaRepositoryInterface extends BaseMediaRepositoryInterface
{
    /**
     * Returns a query builder which is used as the starting point for building media queries,
     * joined with translations for the given locale.
     */
    public function getQueryBuilder(string $localeCode): QueryBuilder;

    /**
     * Creates a paginator which is used to list media.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Media>
     */
    public function createListPaginator(string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for media.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Media>
     */
    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface;
}
