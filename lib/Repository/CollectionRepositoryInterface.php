<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface as BaseCollectionRepositoryInterface;

interface CollectionRepositoryInterface extends BaseCollectionRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    /** @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Collection> */
    public function createListPaginator(): PagerfantaInterface;

    /** @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Collection> */
    public function createSearchPaginator(string $searchText): PagerfantaInterface;
}
