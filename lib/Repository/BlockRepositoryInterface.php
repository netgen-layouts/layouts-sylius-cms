<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface as BaseBlockRepositoryInterface;

interface BlockRepositoryInterface extends BaseBlockRepositoryInterface
{
    /**
     * Creates a paginator which is used to list blocks.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Block>
     */
    public function createListPaginator(): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for blocks.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Block>
     */
    public function createSearchPaginator(string $searchText): PagerfantaInterface;
}
