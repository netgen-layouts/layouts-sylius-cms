<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface as BaseCollectionRepositoryInterface;

interface CollectionRepositoryInterface extends BaseCollectionRepositoryInterface
{
    /**
     * Creates a paginator which is used to list collections.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Collection>
     */
    public function createListPaginator(): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for collections.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Collection>
     */
    public function createSearchPaginator(string $searchText): PagerfantaInterface;
}
