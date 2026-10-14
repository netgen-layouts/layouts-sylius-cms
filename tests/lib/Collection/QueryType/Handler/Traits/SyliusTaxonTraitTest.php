<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Parameters\Parameter;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterList;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\Traits\SyliusTaxonTrait;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Block;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

#[CoversTrait(SyliusTaxonTrait::class)]
final class SyliusTaxonTraitTest extends TestCase
{
    public function testTheCriterionJoinsTheBlocksTaxonsRelation(): void
    {
        $entityManagerStub = self::createStub(EntityManagerInterface::class);
        $entityManagerStub->method('getExpressionBuilder')->willReturn(new Expr());

        $queryBuilder = (new QueryBuilder($entityManagerStub))->select('o')->from(Block::class, 'o');

        $handler = new class {
            use SyliusTaxonTrait;

            public function apply(ParameterCollectionInterface $parameters, QueryBuilder $queryBuilder): void
            {
                $this->addSyliusTaxonCriterion($parameters, $queryBuilder, new Request());
            }
        };

        $handler->apply(
            Query::fromArray([
                'id' => Uuid::v4(),
                'parameters' => new ParameterList([
                    'use_current_taxon' => Parameter::fromArray(['name' => 'use_current_taxon', 'value' => false, 'isEmpty' => false]),
                    'sylius_taxon_id' => Parameter::fromArray(['name' => 'sylius_taxon_id', 'value' => 42, 'isEmpty' => false]),
                ]),
            ]),
            $queryBuilder,
        );

        self::assertSame(
            'SELECT o FROM ' . Block::class . ' o INNER JOIN o.taxons taxons WHERE taxons.id = :taxonId',
            $queryBuilder->getDQL(),
        );
        self::assertSame(42, $queryBuilder->getParameter('taxonId')?->getValue());
    }
}
