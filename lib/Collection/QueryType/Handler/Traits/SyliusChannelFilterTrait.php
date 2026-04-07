<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Parameters\ParameterType as SyliusParameterType;

use function count;
use function in_array;

trait SyliusChannelFilterTrait
{
    /**
     * Builds the parameters for filtering by Sylius channel.
     *
     * @param string[] $groups
     */
    private function buildSyliusChannelFilterParameters(ParameterBuilderInterface $builder, array $groups = []): void
    {
        $builder->add(
            'filter_by_channel',
            ParameterType\Compound\BooleanType::class,
            [
                'groups' => $groups,
            ],
        );

        $builder->get('filter_by_channel')->add(
            'channels',
            SyliusParameterType\ChannelType::class,
            [
                'multiple' => true,
                'groups' => $groups,
            ],
        );

        $builder->get('filter_by_channel')->add(
            'channels_filter',
            ParameterType\ChoiceType::class,
            [
                'required' => true,
                'options' => [
                    'Include channels' => 'include',
                    'Exclude channels' => 'exclude',
                ],
                'groups' => $groups,
            ],
        );
    }

    /**
     * Builds the criteria for filtering by Sylius channel.
     */
    private function addSyliusChannelFilterCriterion(ParameterCollectionInterface $parameterCollection, QueryBuilder $queryBuilder): void
    {
        if ($parameterCollection->getParameter('filter_by_channel')->value !== true) {
            return;
        }

        $channels = $parameterCollection->getParameter('channels')->value ?? [];
        if (count($channels) === 0) {
            return;
        }

        $reverse = $parameterCollection->getParameter('channels_filter')->value !== 'include';

        if (!in_array('channels', $queryBuilder->getAllAliases(), true)) {
            $rootAliases = $queryBuilder->getRootAliases();

            $join = count($rootAliases) === 0 ? 'channels' : $rootAliases[0] . '.channels';

            $queryBuilder->innerJoin($join, 'channels');
        }

        $reverse
            ? $queryBuilder->andWhere($queryBuilder->expr()->notIn('channels.id', ':channels'))
            : $queryBuilder->andWhere($queryBuilder->expr()->in('channels.id', ':channels'));

        $queryBuilder->setParameter(':channels', $channels);
    }
}
