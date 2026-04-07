<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Block;

use Netgen\ContentBrowser\Item\ItemInterface;
use Sylius\CmsPlugin\Entity\BlockInterface as SyliusCmsBlockInterface;

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
        public private(set) SyliusCmsBlockInterface $block,
    ) {}
}
