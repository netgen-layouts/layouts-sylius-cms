<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Media;

use BitBag\SyliusCmsPlugin\Entity\MediaInterface as BitBagMediaInterface;

interface MediaInterface
{
    /**
     * Returns the BitBag media.
     */
    public BitBagMediaInterface $media { get; }
}
