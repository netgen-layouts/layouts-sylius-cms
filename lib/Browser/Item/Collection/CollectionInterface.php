<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\Collection;

use Sylius\CmsPlugin\Entity\CollectionInterface as SyliusCmsCollectionInterface;

interface CollectionInterface
{
    public SyliusCmsCollectionInterface $collection { get; }
}
