<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface as BasePageRepositoryInterface;

interface PageRepositoryInterface extends BasePageRepositoryInterface
{
    /**
     * Creates a query builder for listing pages with translations.
     */
    public function createListQueryBuilder(string $localeCode): QueryBuilder;

    /**
     * Creates a paginator which is used to list pages.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Page>
     */
    public function createListPaginator(string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for pages.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Page>
     */
    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface;
}
