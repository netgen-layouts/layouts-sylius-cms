<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\ColumnProvider\Section;

use Netgen\ContentBrowser\Item\ColumnProvider\ColumnValueProviderInterface;
use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Section\SectionInterface;

final class Code implements ColumnValueProviderInterface
{
    public function getValue(ItemInterface $item): ?string
    {
        if (!$item instanceof SectionInterface) {
            return null;
        }

        return $item->section->getCode();
    }
}
