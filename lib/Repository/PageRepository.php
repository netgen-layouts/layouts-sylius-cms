<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\PageRepository as BasePageRepository;

final class PageRepository extends BasePageRepository implements PageRepositoryInterface
{
    /**
     * Joins the translation in the provided locale without requiring one, so a page
     * without a translation in the current locale is still listed (named through
     * its fallback locale), as the admin's own lists do.
     */
    public function getQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->setParameter('locale', $localeCode);
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
                    'translation.slug LIKE :search',
                    'translation.title LIKE :search',
                    'translation.teaserTitle LIKE :search',
                ),
            )
            ->setParameter('search', '%' . $searchText . '%');

        return $this->getPaginator($queryBuilder);
    }
}
