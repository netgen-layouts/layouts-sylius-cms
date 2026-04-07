<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Section;

use Netgen\ContentBrowser\Item\ItemInterface;
use Sylius\CmsPlugin\Entity\SectionInterface as SyliusCmsSectionInterface;

final class Item implements ItemInterface, SectionInterface
{
    public int $value {
        get => $this->section->getId();
    }

    public string $name {
        get => (string) $this->section->getName();
    }

    public true $isVisible {
        get => true;
    }

    public true $isSelectable {
        get => true;
    }

    public function __construct(
        public private(set) SyliusCmsSectionInterface $section,
    ) {}
}
