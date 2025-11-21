<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Block;

use BitBag\SyliusCmsPlugin\Entity\BlockInterface as BitBagBlockInterface;

interface BlockInterface
{
    /**
     * Returns the BitBag block.
     */
    public BitBagBlockInterface $block { get; }
}
