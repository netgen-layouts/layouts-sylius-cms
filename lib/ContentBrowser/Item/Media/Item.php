<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Media;

use BitBag\SyliusCmsPlugin\Entity\MediaInterface as BitBagMediaInterface;
use Netgen\ContentBrowser\Item\ItemInterface;

final class Item implements ItemInterface, MediaInterface
{
    public int $value {
        get => $this->media->getId();
    }

    public string $name {
        get => $this->media->getName() ?? $this->media->getCode() ?? '';
    }

    public true $isVisible {
        get => true;
    }

    public true $isSelectable {
        get => true;
    }

    public function __construct(
        public private(set) BitBagMediaInterface $media,
    ) {}
}
