<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\BlockRepository as BaseBlockRepository;

final class BlockRepository extends BaseBlockRepository implements BlockRepositoryInterface
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
