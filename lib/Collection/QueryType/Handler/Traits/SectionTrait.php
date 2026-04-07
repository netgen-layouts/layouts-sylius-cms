<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Cms\Parameters\ParameterType as SyliusCmsParameterType;
use Sylius\CmsPlugin\Entity\SectionInterface;
use Symfony\Component\HttpFoundation\Request;

use function count;
use function in_array;

trait SectionTrait
{
    /**
     * Builds the parameters for filtering by specific or contextual Sylius CMS section.
     *
     * @param string[] $groups
     */
    private function buildSectionParameters(ParameterBuilderInterface $builder, array $groups = []): void
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
            'section_id',
            SyliusCmsParameterType\SectionType::class,
            [
                'groups' => $groups,
            ],
        );
    }

    private function isSectionContextual(ParameterCollectionInterface $parameterCollection): bool
    {
        return $parameterCollection->getParameter('use_current_section')->value === true;
    }

    /**
     * Builds the criteria for filtering by Sylius CMS section.
     */
    private function addSectionCriterion(
        ParameterCollectionInterface $parameterCollection,
        QueryBuilder $queryBuilder,
        ?Request $request,
    ): void {
        $useCurrentSection = $parameterCollection->getParameter('use_current_section')->value;
        $sectionId = $parameterCollection->getParameter('section_id')->value;

        if ($useCurrentSection === true) {
            $sectionId = $this->getCurrentSectionId($request);
        }

        if ($sectionId === null) {
            return;
        }

        if (!in_array('sections', $queryBuilder->getAllAliases(), true)) {
            $rootAliases = $queryBuilder->getRootAliases();

            $join = count($rootAliases) === 0 ? 'sections' : $rootAliases[0] . '.sections';

            $queryBuilder->innerJoin($join, 'sections');
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq('sections.id', ':sectionId'));
        $queryBuilder->setParameter(':sectionId', $sectionId);
    }

    private function getCurrentSectionId(?Request $request): ?int
    {
        if (!$request instanceof Request) {
            return null;
        }

        $section = $request->attributes->get('nglayouts_sylius_cms_section');
        if (!$section instanceof SectionInterface) {
            return null;
        }

        return $section->getId();
    }
}
