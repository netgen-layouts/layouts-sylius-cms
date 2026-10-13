<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Repository;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Sylius\Cms\Repository\PageRepository;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PageRepository::class)]
final class PageRepositoryTest extends TestCase
{
    private QueryBuilder $queryBuilder;

    private PageRepository $repository;

    protected function setUp(): void
    {
        $entityManagerStub = self::createStub(EntityManagerInterface::class);
        $entityManagerStub->method('getConfiguration')->willReturn(new Configuration());
        $entityManagerStub->method('getExpressionBuilder')->willReturn(new Expr());
        $entityManagerStub->method('createQueryBuilder')->willReturnCallback(
            fn (): QueryBuilder => $this->queryBuilder = new QueryBuilder($entityManagerStub),
        );
        $entityManagerStub->method('createQuery')->willReturnCallback(
            static fn (string $dql): Query => (new Query($entityManagerStub))->setDQL($dql),
        );

        $this->repository = new PageRepository($entityManagerStub, new ClassMetadata(Page::class));
    }

    public function testGetQueryBuilderJoinsTheLocaleWithoutRequiringATranslation(): void
    {
        $queryBuilder = $this->repository->getQueryBuilder('en');

        self::assertSame(
            'SELECT o, translation FROM ' . Page::class . ' o LEFT JOIN o.translations translation WITH translation.locale = :locale',
            $queryBuilder->getDQL(),
        );
        self::assertSame('en', $queryBuilder->getParameter('locale')?->getValue());
    }

    public function testCreateListPaginator(): void
    {
        $this->repository->createListPaginator('en');

        self::assertStringNotContainsString('WHERE', $this->queryBuilder->getDQL());
    }

    public function testCreateSearchPaginator(): void
    {
        $this->repository->createSearchPaginator('contact', 'en');

        self::assertSame(
            'SELECT o, translation FROM ' . Page::class . ' o LEFT JOIN o.translations translation WITH translation.locale = :locale'
            . ' WHERE o.code LIKE :search OR o.name LIKE :search OR translation.slug LIKE :search OR translation.title LIKE :search',
            $this->queryBuilder->getDQL(),
        );
        self::assertSame('%contact%', $this->queryBuilder->getParameter('search')?->getValue());
    }
}
