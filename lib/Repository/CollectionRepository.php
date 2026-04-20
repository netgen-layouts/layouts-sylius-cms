<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\CollectionRepository as BaseCollectionRepository;

final class CollectionRepository extends BaseCollectionRepository implements CollectionRepositoryInterface
{
    public function createListPaginator(): PagerfantaInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $this->getPaginator($queryBuilder);
    }

    public function createSearchPaginator(string $searchText): PagerfantaInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'o.code LIKE :search',
                    'o.name LIKE :search',
                ),
            )
            ->setParameter('search', '%' . $searchText . '%');

        return $this->getPaginator($queryBuilder);
    }
}
