<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Media;

use Netgen\ContentBrowser\Item\ItemInterface;
use Sylius\CmsPlugin\Entity\MediaInterface as SyliusCmsMediaInterface;

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
        public private(set) SyliusCmsMediaInterface $media,
    ) {}
}
