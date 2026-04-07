<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Netgen\Layouts\Sylius\Cms\Repository\MediaRepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Throwable;

final class MediaValueLoader implements ValueLoaderInterface
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepository,
    ) {}

    public function load(int|string $id): ?ResourceInterface
    {
        try {
            return $this->mediaRepository->find($id);
        } catch (Throwable) {
            return null;
        }
    }

    public function loadByRemoteId(int|string $remoteId): ?ResourceInterface
    {
        return $this->load($remoteId);
    }
}
