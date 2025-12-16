<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueConverter;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use Netgen\Layouts\Item\ValueConverterInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\BitBag\SyliusCmsPlugin\Entity\SectionInterface>
 */
final class SectionValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof SectionInterface;
    }

    public function getValueType(object $object): string
    {
        return 'bitbag_section';
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

    public function getObject(object $object): SectionInterface
    {
        return $object;
    }
}
