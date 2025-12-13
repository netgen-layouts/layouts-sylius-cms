<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler\Traits;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\BitBag\Parameters\ParameterType as BitBagParameterType;
use Symfony\Component\HttpFoundation\Request;

use function count;
use function in_array;

trait BitBagSectionTrait
{
    /**
     * Builds the parameters for filtering by specific or contextual BitBag section.
     *
     * @param string[] $groups
     */
    private function buildBitBagSectionParameters(ParameterBuilderInterface $builder, array $groups = []): void
    {
        $builder->add(
            'use_current_section',
            ParameterType\Compound\BooleanType::class,
            [
                'reverse' => true,
                'groups' => $groups,
            ],
        );

        $builder->get('use_current_section')->add(
            'bitbag_section_id',
            BitBagParameterType\SectionType::class,
            [
                'groups' => $groups,
            ],
        );
    }

    private function isBitBagSectionContextual(ParameterCollectionInterface $parameterCollection): bool
    {
        return $parameterCollection->getParameter('use_current_section')->value === true;
    }

    /**
     * Builds the criteria for filtering by BitBag section.
     */
    private function addBitBagSectionCriterion(
        ParameterCollectionInterface $parameterCollection,
        QueryBuilder $queryBuilder,
        ?Request $request,
    ): void {
        $useCurrentSection = $parameterCollection->getParameter('use_current_section')->value;
        $bitBagSectionId = $parameterCollection->getParameter('bitbag_section_id')->value;

        if ($useCurrentSection === true) {
            $bitBagSectionId = $this->getCurrentSectionId($request);
        }

        if ($bitBagSectionId === null) {
            return;
        }

        if (!in_array('sections', $queryBuilder->getAllAliases(), true)) {
            $rootAliases = $queryBuilder->getRootAliases();

            $join = count($rootAliases) === 0 ? 'sections' : $rootAliases[0] . '.sections';

            $queryBuilder->innerJoin($join, 'sections');
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq('sections.id', ':sectionId'));
        $queryBuilder->setParameter(':sectionId', (int) $bitBagSectionId);
    }

    private function getCurrentSectionId(?Request $request): ?int
    {
        if (!$request instanceof Request) {
            return null;
        }

        $section = $request->attributes->get('nglayouts_sylius_bitbag_section');
        if (!$section instanceof SectionInterface) {
            return null;
        }

        return $section->getId();
    }
}
