<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueConverter;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use Netgen\Layouts\Item\ValueConverterInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\BitBag\SyliusCmsPlugin\Entity\PageInterface>
 */
final class PageValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof PageInterface;
    }

    public function getValueType(object $object): string
    {
        return 'bitbag_page';
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

    public function getObject(object $object): PageInterface
    {
        return $object;
    }
}
