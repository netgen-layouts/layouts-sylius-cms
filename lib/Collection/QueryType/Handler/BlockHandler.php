<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler;

use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\CollectionTrait;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\EnabledTrait;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\SortingTrait;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\SyliusChannelFilterTrait;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\SyliusProductTrait;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\SyliusTaxonTrait;
use Netgen\Layouts\Sylius\Cms\Repository\BlockRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use function max;

use const PHP_INT_MAX;

final class BlockHandler implements QueryTypeHandlerInterface
{
    use CollectionTrait;
    use EnabledTrait;
    use SortingTrait;
    use SyliusChannelFilterTrait;
    use SyliusProductTrait;
    use SyliusTaxonTrait;

    /**
     * @var array<string, string>
     */
    private array $sortingOptions = [
        'Name' => 'name',
        'Code' => 'code',
    ];

    public function __construct(
        private BlockRepositoryInterface $blockRepository,
        private RequestStack $requestStack,
    ) {}

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $this->buildSyliusProductParameters($builder);
        $this->buildSyliusTaxonParameters($builder);
        $this->buildCollectionParameters($builder);
        $this->buildSyliusChannelFilterParameters($builder);
        $this->buildSortingParameters($builder, $this->sortingOptions);
    }

    public function getValues(Query $query, int $offset = 0, ?int $limit = null): iterable
    {
        $queryBuilder = $this->blockRepository->createListQueryBuilder();

        $request = $this->requestStack->getCurrentRequest();

        $this->addSyliusProductCriterion($query, $queryBuilder, $request);
        $this->addSyliusTaxonCriterion($query, $queryBuilder, $request);
        $this->addCollectionCriterion($query, $queryBuilder, $request);
        $this->addSyliusChannelFilterCriterion($query, $queryBuilder);
        $this->addEnabledCriterion($queryBuilder);
        $this->addSortingClause($query, $queryBuilder);

        $limit = max(0, $limit ?? PHP_INT_MAX);
        $offset = max(0, $offset);

        return $queryBuilder->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getCount(Query $query): int
    {
        $queryBuilder = $this->blockRepository->createListQueryBuilder();

        $request = $this->requestStack->getCurrentRequest();

        $this->addSyliusProductCriterion($query, $queryBuilder, $request);
        $this->addSyliusTaxonCriterion($query, $queryBuilder, $request);
        $this->addCollectionCriterion($query, $queryBuilder, $request);
        $this->addSyliusChannelFilterCriterion($query, $queryBuilder);
        $this->addEnabledCriterion($queryBuilder);

        return (int) $queryBuilder
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function isContextual(Query $query): bool
    {
        return $this->isSyliusProductContextual($query)
            || $this->isSyliusTaxonContextual($query)
            || $this->isCollectionContextual($query);
    }
}
