<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\PageRepository as BasePageRepository;

final class PageRepository extends BasePageRepository implements PageRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createTranslationBasedQueryBuilder($localeCode);
    }

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
                    'translation.slug LIKE :search',
                    'translation.title LIKE :search',
                ),
            )
            ->setParameter('search', '%' . $searchText . '%');

        return $this->getPaginator($queryBuilder);
    }
}
