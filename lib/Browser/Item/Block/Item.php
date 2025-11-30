<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\Item\Block;

use BitBag\SyliusCmsPlugin\Entity\BlockInterface as BitBagBlockInterface;
use Netgen\ContentBrowser\Item\ItemInterface;

final class Item implements ItemInterface, BlockInterface
{
    public int $value {
        get => $this->block->getId();
    }

    public string $name {
        get => (string) $this->block->getName();
    }

    public true $isVisible {
        get => true;
    }

    public true $isSelectable {
        get => true;
    }

    public function __construct(
        public private(set) BitBagBlockInterface $block,
    ) {}
}
