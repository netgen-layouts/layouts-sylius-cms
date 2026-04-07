<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueConverter;

use Netgen\Layouts\Item\ValueConverterInterface;
use Sylius\CmsPlugin\Entity\BlockInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\Sylius\CmsPlugin\Entity\BlockInterface>
 */
final class BlockValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof BlockInterface;
    }

    public function getValueType(object $object): string
    {
        return 'sylius_cms_block';
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

    public function getIsVisible(object $object): bool
    {
        return $object->isEnabled();
    }

    public function getObject(object $object): BlockInterface
    {
        return $object;
    }
}
