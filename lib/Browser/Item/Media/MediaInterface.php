<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Media;

use Sylius\CmsPlugin\Entity\MediaInterface as SyliusCmsMediaInterface;

interface MediaInterface
{
    /**
     * Returns the Sylius CMS media.
     */
    public SyliusCmsMediaInterface $media { get; }
}
