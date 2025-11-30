<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\ColumnProvider\Media;

use Netgen\ContentBrowser\Item\ColumnProvider\ColumnValueProviderInterface;
use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Media\MediaInterface;

final class Code implements ColumnValueProviderInterface
{
    public function getValue(ItemInterface $item): ?string
    {
        if (!$item instanceof MediaInterface) {
            return null;
        }

        return $item->media->getCode();
    }
}
