<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\Item\Page;

use BitBag\SyliusCmsPlugin\Entity\PageInterface as BitBagPageInterface;

interface PageInterface
{
    /**
     * Returns the BitBag page.
     */
    public BitBagPageInterface $page { get; }
}
