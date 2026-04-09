<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Collection;

use Netgen\ContentBrowser\Item\ColumnProvider\ColumnValueProviderInterface;
use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Collection\CollectionInterface;

final class Code implements ColumnValueProviderInterface
{
    public function getValue(ItemInterface $item): ?string
    {
        if (!$item instanceof CollectionInterface) {
            return null;
        }

        return $item->collection->getCode();
    }
}
