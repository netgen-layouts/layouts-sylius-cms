<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueLoader;

use BitBag\SyliusCmsPlugin\Entity\BlockInterface;
use Netgen\Layouts\Item\ValueLoaderInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\BlockRepositoryInterface;
use Throwable;

final class BlockValueLoader implements ValueLoaderInterface
{
    public function __construct(
        private BlockRepositoryInterface $blockRepository,
    ) {}

    public function load(int|string $id): ?BlockInterface
    {
        try {
            return $this->blockRepository->find($id);
        } catch (Throwable) {
            return null;
        }
    }

    public function loadByRemoteId(int|string $remoteId): ?BlockInterface
    {
        return $this->load($remoteId);
    }
}
