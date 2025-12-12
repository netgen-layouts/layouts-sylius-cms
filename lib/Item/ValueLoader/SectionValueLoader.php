<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Throwable;

final class SectionValueLoader implements ValueLoaderInterface
{
    public function __construct(
        private SectionRepositoryInterface $sectionRepository,
    ) {}

    public function load(int|string $id): ?ResourceInterface
    {
        try {
            return $this->sectionRepository->find($id);
        } catch (Throwable) {
            return null;
        }
    }

    public function loadByRemoteId(int|string $remoteId): ?ResourceInterface
    {
        return $this->load($remoteId);
    }
}
