<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface as BaseMediaRepositoryInterface;

interface MediaRepositoryInterface extends BaseMediaRepositoryInterface
{
    /** @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Media> */
    public function createListPaginator(string $localeCode): PagerfantaInterface;

    /** @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Media> */
    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface;
}
