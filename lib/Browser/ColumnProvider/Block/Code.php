<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\ColumnProvider\Block;

use Netgen\ContentBrowser\Item\ColumnProvider\ColumnValueProviderInterface;
use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Block\BlockInterface;

final class Code implements ColumnValueProviderInterface
{
    public function getValue(ItemInterface $item): ?string
    {
        if (!$item instanceof BlockInterface) {
            return null;
        }

        return $item->block->getCode();
    }
}
