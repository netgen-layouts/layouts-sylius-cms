<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Section;

use Sylius\CmsPlugin\Entity\SectionInterface as SyliusCmsSectionInterface;

interface SectionInterface
{
    /**
     * Returns the Sylius CMS section.
     */
    public SyliusCmsSectionInterface $section { get; }
}
