<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Section;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface as BitBagSectionInterface;

interface SectionInterface
{
    /**
     * Returns the BitBag section.
     */
    public BitBagSectionInterface $section { get; }
}
