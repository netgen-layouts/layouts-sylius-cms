<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueConverter;

use Netgen\Layouts\Item\ValueConverterInterface;
use Sylius\CmsPlugin\Entity\MediaInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\Sylius\CmsPlugin\Entity\MediaInterface>
 */
final class MediaValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof MediaInterface;
    }

    public function getValueType(object $object): string
    {
        return 'sylius_cms_media';
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
        return $object->getName() ?? $object->getCode() ?? '';
    }

    public function getIsVisible(object $object): bool
    {
        return $object->isEnabled();
    }

    public function getObject(object $object): MediaInterface
    {
        return $object;
    }
}
