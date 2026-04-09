<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface as BaseMediaRepositoryInterface;

interface MediaRepositoryInterface extends BaseMediaRepositoryInterface
{
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
