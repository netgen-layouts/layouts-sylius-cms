<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;

use function count;
use function in_array;
use function str_starts_with;

trait SortingTrait
{
    /**
     * Builds the parameters for sorting options.
     *
     * @param array<string, string> $sortingOptions
     * @param string[] $groups
     */
    private function buildSortingParameters(ParameterBuilderInterface $builder, array $sortingOptions, array $groups = []): void
    {
        $builder->add(
            'sort_type',
            ParameterType\ChoiceType::class,
            [
                'required' => true,
                'options' => $sortingOptions,
                'groups' => $groups,
            ],
        );

        $builder->add(
            'sort_direction',
            ParameterType\ChoiceType::class,
            [
                'required' => true,
                'options' => [
                    'Descending' => 'DESC',
                    'Ascending' => 'ASC',
                ],
                'groups' => $groups,
            ],
        );
    }

    /**
     * Builds the query for Sylius CMS sorting.
     */
    private function addSortingClause(ParameterCollectionInterface $parameterCollection, QueryBuilder $queryBuilder): void
    {
        $sortField = $parameterCollection->getParameter('sort_type')->value;
        $sortDirection = $parameterCollection->getParameter('sort_direction')->value;
        $rootAliases = $queryBuilder->getRootAliases();

        if (!in_array('translation', $queryBuilder->getAllAliases(), true)) {
            $join = count($rootAliases) === 0 ? 'translations' : $rootAliases[0] . '.translations';

            $queryBuilder->innerJoin($join, 'translation');
        }

        if (!str_starts_with($sortField, 'translation.') && count($rootAliases) !== 0) {
            $sortField = $rootAliases[0] . '.' . $sortField;
        }

        $queryBuilder->orderBy($sortField, $sortDirection);
    }
}
