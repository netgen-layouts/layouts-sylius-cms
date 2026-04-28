<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint as SyliusCmsConstraints;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Collection extends TargetType implements ValueObjectProviderInterface
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_cms_collection';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'int'),
            new Constraints\Positive(),
            new SyliusCmsConstraints\Collection(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $collection = $request->attributes->get('nglayouts_sylius_cms_collection');

        return $collection instanceof CollectionInterface ? $collection->getId() : null;
    }

    public function getValueObject(int|string $value): ?ResourceInterface
    {
        return $this->collectionRepository->find((int) $value);
    }
}
