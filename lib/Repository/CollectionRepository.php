<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\CollectionRepository as BaseCollectionRepository;

final class CollectionRepository extends BaseCollectionRepository implements CollectionRepositoryInterface
{
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    public function createListPaginator(): PagerfantaInterface
    {
        return $this->getPaginator($this->getQueryBuilder());
    }

    public function createSearchPaginator(string $searchText): PagerfantaInterface
    {
        $queryBuilder = $this->getQueryBuilder();
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
