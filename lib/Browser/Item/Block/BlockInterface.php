<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Block;

use Sylius\CmsPlugin\Entity\BlockInterface as SyliusCmsBlockInterface;

interface BlockInterface
{
    /**
     * Returns the Sylius CMS block.
     */
    public SyliusCmsBlockInterface $block { get; }
}
