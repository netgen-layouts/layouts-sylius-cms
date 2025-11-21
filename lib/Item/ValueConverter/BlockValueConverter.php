<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueConverter;

use BitBag\SyliusCmsPlugin\Entity\BlockInterface;
use Netgen\Layouts\Item\ValueConverterInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\BitBag\SyliusCmsPlugin\Entity\BlockInterface>
 */
final class BlockValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof BlockInterface;
    }

    public function getValueType(object $object): string
    {
        return 'bitbag_block';
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
