<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Collection;

use Netgen\ContentBrowser\Item\ItemInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface as SyliusCmsCollectionInterface;

final class Item implements ItemInterface, CollectionInterface
{
    public int $value {
        get => $this->collection->getId();
    }

    public string $name {
        get => (string) $this->collection->getName();
    }

    public true $isVisible {
        get => true;
    }

    public true $isSelectable {
        get => true;
    }

    public function __construct(
        public private(set) SyliusCmsCollectionInterface $collection,
    ) {}
}
