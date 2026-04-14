<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\BlockRepository as BaseBlockRepository;

final class BlockRepository extends BaseBlockRepository implements BlockRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode = ''): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    public function createListPaginator(): PagerfantaInterface
    {
        $queryBuilder = $this->createListQueryBuilder();

        return $this->getPaginator($queryBuilder);
    }

    public function createSearchPaginator(string $searchText): PagerfantaInterface
    {
        $queryBuilder = $this->createListQueryBuilder();
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
