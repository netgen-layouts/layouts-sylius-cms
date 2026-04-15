<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface as BaseBlockRepositoryInterface;

interface BlockRepositoryInterface extends BaseBlockRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode = ''): QueryBuilder;

    /** @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Block> */
    public function createListPaginator(): PagerfantaInterface;

    /** @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Block> */
    public function createSearchPaginator(string $searchText): PagerfantaInterface;
}
