<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Page;

use Sylius\CmsPlugin\Entity\PageInterface as SyliusCmsPageInterface;

interface PageInterface
{
    /**
     * Returns the Sylius CMS page.
     */
    public SyliusCmsPageInterface $page { get; }
}
