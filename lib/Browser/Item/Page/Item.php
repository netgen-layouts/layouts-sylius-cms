<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Page;

use Netgen\ContentBrowser\Item\ItemInterface;
use Sylius\CmsPlugin\Entity\PageInterface as SyliusCmsPageInterface;

final class Item implements ItemInterface, PageInterface
{
    public int $value {
        get => $this->page->getId();
    }

    public string $name {
        get => (string) $this->page->getName();
    }

    public true $isVisible {
        get => true;
    }

    public true $isSelectable {
        get => true;
    }

    public function __construct(
        public private(set) SyliusCmsPageInterface $page,
    ) {}
}
