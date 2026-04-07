<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;

use function count;

trait EnabledTrait
{
    /**
     * Builds the criteria for filtering only enabled entities.
     */
    private function addEnabledCriterion(QueryBuilder $queryBuilder): void
    {
        $field = count($queryBuilder->getRootAliases()) > 0
            ? $queryBuilder->getRootAliases()[0] . '.enabled'
            : 'enabled';

        $queryBuilder->andWhere($queryBuilder->expr()->eq($field, true));
    }
}
