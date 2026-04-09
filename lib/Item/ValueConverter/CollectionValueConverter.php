<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueConverter;

use Netgen\Layouts\Item\ValueConverterInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\Sylius\CmsPlugin\Entity\CollectionInterface>
 */
final class CollectionValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof CollectionInterface;
    }

    public function getValueType(object $object): string
    {
        return 'sylius_cms_collection';
    }

    public function getId(object $object): int
    {
        return $object->getId();
    }

    public function getRemoteId(object $object): int
    {
        return $object->getId();
    }

    public function getName(object $object): string
    {
        return (string) $object->getName();
    }

    public function getIsVisible(object $object): true
    {
        return true;
    }

    public function getObject(object $object): CollectionInterface
    {
        return $object;
    }
}
