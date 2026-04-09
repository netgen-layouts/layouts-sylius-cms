<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\MediaRepository as BaseMediaRepository;

final class MediaRepository extends BaseMediaRepository implements MediaRepositoryInterface
{
    public function createListPaginator(string $localeCode): PagerfantaInterface
    {
        $queryBuilder = $this->createListQueryBuilder($localeCode);

        return $this->getPaginator($queryBuilder);
    }

    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface
    {
        $queryBuilder = $this->createListQueryBuilder($localeCode);
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
