<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Parameters\ParameterType as SyliusParameterType;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;

use function count;
use function in_array;

trait SyliusTaxonTrait
{
    /**
     * Builds the parameters for filtering by specific or contextual Sylius taxon.
     *
     * @param string[] $groups
     */
    private function buildSyliusTaxonParameters(ParameterBuilderInterface $builder, array $groups = []): void
    {
        $builder->add(
            'use_current_taxon',
            ParameterType\Compound\BooleanType::class,
            [
                'reverse' => true,
                'groups' => $groups,
            ],
        );

        $builder->get('use_current_taxon')->add(
            'sylius_taxon_id',
            SyliusParameterType\TaxonType::class,
            [
                'groups' => $groups,
            ],
        );
    }

    private function isSyliusTaxonContextual(ParameterCollectionInterface $parameterCollection): bool
    {
        return $parameterCollection->getParameter('use_current_taxon')->value === true;
    }

    /**
     * Builds the criteria for filtering by Sylius taxon.
     */
    private function addSyliusTaxonCriterion(
        ParameterCollectionInterface $parameterCollection,
        QueryBuilder $queryBuilder,
        ?Request $request,
    ): void {
        $useCurrentTaxon = $parameterCollection->getParameter('use_current_taxon')->value;
        $syliusTaxonId = $parameterCollection->getParameter('sylius_taxon_id')->value;

        if ($useCurrentTaxon === true) {
            $syliusTaxonId = $this->getCurrentTaxonId($request);
        }

        if ($syliusTaxonId === null) {
            return;
        }

        if (!in_array('taxonomies', $queryBuilder->getAllAliases(), true)) {
            $rootAliases = $queryBuilder->getRootAliases();

            $join = count($rootAliases) === 0 ? 'taxonomies' : $rootAliases[0] . '.taxonomies';

            $queryBuilder->innerJoin($join, 'taxonomies');
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq('taxonomies.id', ':taxonId'));
        $queryBuilder->setParameter(':taxonId', (int) $syliusTaxonId);
    }

    private function getCurrentTaxonId(?Request $request): ?int
    {
        if (!$request instanceof Request) {
            return null;
        }

        $taxon = $request->attributes->get('nglayouts_sylius_resource');
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        return $taxon->getId();
    }
}
