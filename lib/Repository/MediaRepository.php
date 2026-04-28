<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\MediaRepository as BaseMediaRepository;

final class MediaRepository extends BaseMediaRepository implements MediaRepositoryInterface
{
    public function getQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createListQueryBuilder($localeCode);
    }

    public function createListPaginator(string $localeCode): PagerfantaInterface
    {
        return $this->getPaginator($this->getQueryBuilder($localeCode));
    }

    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface
    {
        $queryBuilder = $this->getQueryBuilder($localeCode);
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'o.code LIKE :search',
                    'o.name LIKE :search',
                    'translation.content LIKE :search',
                    'translation.alt LIKE :search',
                    'translation.link LIKE :search',
                ),
            )
            ->setParameter('search', '%' . $searchText . '%');

        return $this->getPaginator($queryBuilder);
    }
}
