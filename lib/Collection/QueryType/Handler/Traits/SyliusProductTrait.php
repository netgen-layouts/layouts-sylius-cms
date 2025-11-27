<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Collection\QueryType\Handler\Traits;

use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterCollectionInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Parameters\ParameterType as SyliusParameterType;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;

use function count;
use function in_array;

trait SyliusProductTrait
{
    /**
     * Builds the parameters for filtering by specific or contextual Sylius product.
     *
     * @param string[] $groups
     */
    private function buildSyliusProductParameters(ParameterBuilderInterface $builder, array $groups = []): void
    {
        $builder->add(
            'use_current_product',
            ParameterType\Compound\BooleanType::class,
            [
                'reverse' => true,
                'groups' => $groups,
            ],
        );

        $builder->get('use_current_product')->add(
            'sylius_product_id',
            SyliusParameterType\ProductType::class,
            [
                'groups' => $groups,
            ],
        );
    }

    private function isSyliusProductContextual(ParameterCollectionInterface $parameterCollection): bool
    {
        return $parameterCollection->getParameter('use_current_product')->value === true;
    }

    /**
     * Builds the criteria for filtering by Sylius product.
     */
    private function addSyliusProductCriterion(
        ParameterCollectionInterface $parameterCollection,
        QueryBuilder $queryBuilder,
        ?Request $request,
    ): void {
        $useCurrentProduct = $parameterCollection->getParameter('use_current_product')->value === true;
        $syliusProductId = $parameterCollection->getParameter('sylius_product_id')->value;

        if ($useCurrentProduct === true) {
            $syliusProductId = $this->getCurrentProductId($request);
        }

        if ($syliusProductId === null) {
            return;
        }

        if (!in_array('products', $queryBuilder->getAllAliases(), true)) {
            $rootAliases = $queryBuilder->getRootAliases();

            $join = count($rootAliases) === 0 ? 'products' : $rootAliases[0] . '.products';

            $queryBuilder->innerJoin($join, 'products');
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq('products.id', ':productId'));
        $queryBuilder->setParameter(':productId', (int) $syliusProductId);
    }

    private function getCurrentProductId(?Request $request): ?int
    {
        if (!$request instanceof Request) {
            return null;
        }

        $product = $request->attributes->get('nglayouts_sylius_resource');

        if (!$product instanceof ProductInterface) {
            return null;
        }

        return $product->getId();
    }
}
