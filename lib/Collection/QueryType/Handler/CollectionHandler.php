<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler;

use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\SortingTrait;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepository;

use function max;

use const PHP_INT_MAX;

final class CollectionHandler implements QueryTypeHandlerInterface
{
    use SortingTrait;

    /**
     * @var array<string, string>
     */
    private array $sortingOptions = [
        'Name' => 'name',
        'Code' => 'code',
    ];

    public function __construct(
        private CollectionRepository $collectionRepository,
    ) {}

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $this->buildSortingParameters($builder, $this->sortingOptions);
    }

    public function getValues(Query $query, int $offset = 0, ?int $limit = null): iterable
    {
        $queryBuilder = $this->collectionRepository->createQueryBuilder('o');

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
        $queryBuilder = $this->collectionRepository->createQueryBuilder('o');

        return (int) $queryBuilder
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function isContextual(Query $query): false
    {
        return false;
    }
}
