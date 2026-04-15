<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Cms\Parameters\ParameterType as SyliusCmsParameterType;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Symfony\Component\HttpFoundation\Request;

use function count;
use function in_array;

trait CollectionTrait
{
    /** @param string[] $groups */
    private function buildCollectionParameters(ParameterBuilderInterface $builder, array $groups = []): void
    {
        $builder->add(
            'use_current_collection',
            ParameterType\Compound\BooleanType::class,
            [
                'reverse' => true,
                'groups' => $groups,
            ],
        );

        $builder->get('use_current_collection')->add(
            'collection_id',
            SyliusCmsParameterType\CollectionType::class,
            [
                'groups' => $groups,
            ],
        );
    }

    private function isCollectionContextual(ParameterCollectionInterface $parameterCollection): bool
    {
        return $parameterCollection->getParameter('use_current_collection')->value === true;
    }

    private function addCollectionCriterion(
        ParameterCollectionInterface $parameterCollection,
        QueryBuilder $queryBuilder,
        ?Request $request,
    ): void {
        $useCurrentCollection = $parameterCollection->getParameter('use_current_collection')->value;
        $collectionId = $parameterCollection->getParameter('collection_id')->value;

        if ($useCurrentCollection === true) {
            $collectionId = $this->getCurrentCollectionId($request);
        }

        if ($collectionId === null) {
            return;
        }

        if (!in_array('collections', $queryBuilder->getAllAliases(), true)) {
            $rootAliases = $queryBuilder->getRootAliases();

            $join = count($rootAliases) === 0 ? 'collections' : $rootAliases[0] . '.collections';

            $queryBuilder->innerJoin($join, 'collections');
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq('collections.id', ':collectionId'));
        $queryBuilder->setParameter(':collectionId', $collectionId);
    }

    private function getCurrentCollectionId(?Request $request): ?int
    {
        if (!$request instanceof Request) {
            return null;
        }

        $collection = $request->attributes->get('nglayouts_sylius_cms_collection');
        if (!$collection instanceof CollectionInterface) {
            return null;
        }

        return $collection->getId();
    }
}
