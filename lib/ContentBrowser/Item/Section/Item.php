<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Section;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface as BitBagSectionInterface;
use Netgen\ContentBrowser\Item\ItemInterface;

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
        private(set) BitBagSectionInterface $section,
    ) {}
}
