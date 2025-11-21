<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler;

use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler\Traits\BitBagEnabledTrait;
use Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler\Traits\BitBagSortingTrait;
use Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler\Traits\SyliusChannelFilterTrait;
use Netgen\Layouts\Sylius\BitBag\Repository\FrequentlyAskedQuestionRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

use function max;

use const PHP_INT_MAX;

final class FrequentlyAskedQuestionHandler implements QueryTypeHandlerInterface
{
    use BitBagEnabledTrait;
    use BitBagSortingTrait;
    use SyliusChannelFilterTrait;

    /**
     * @var array<string, string>
     */
    private array $sortingOptions = [
        'Position' => 'position',
        'Question' => 'translation.question',
        'Answer' => 'translation.answer',
        'Code' => 'code',
    ];

    public function __construct(
        private FrequentlyAskedQuestionRepositoryInterface $frequentlyAskedQuestionRepository,
        private LocaleContextInterface $localeContext,
    ) {}

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $this->buildSyliusChannelFilterParameters($builder);
        $this->buildBitBagSortingParameters($builder, $this->sortingOptions);
    }

    public function getValues(Query $query, int $offset = 0, ?int $limit = null): iterable
    {
        $queryBuilder = $this->frequentlyAskedQuestionRepository->createListQueryBuilder(
            $this->localeContext->getLocaleCode(),
        );

        $this->addSyliusChannelFilterCriterion($query, $queryBuilder);
        $this->addBitBagEnabledCriterion($queryBuilder);
        $this->addBitBagSortingClause($query, $queryBuilder);

        $limit = max(0, $limit ?? PHP_INT_MAX);
        $offset = max(0, $offset);

        return $queryBuilder->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getCount(Query $query): int
    {
        $queryBuilder = $this->frequentlyAskedQuestionRepository->createListQueryBuilder(
            $this->localeContext->getLocaleCode(),
        );

        $this->addSyliusChannelFilterCriterion($query, $queryBuilder);
        $this->addBitBagEnabledCriterion($queryBuilder);

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
