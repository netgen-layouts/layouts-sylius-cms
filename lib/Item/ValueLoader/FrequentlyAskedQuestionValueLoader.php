<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\FrequentlyAskedQuestionRepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Throwable;

final class FrequentlyAskedQuestionValueLoader implements ValueLoaderInterface
{
    public function __construct(
        private FrequentlyAskedQuestionRepositoryInterface $frequentlyAskedQuestionRepository,
    ) {}

    public function load(int|string $id): ?ResourceInterface
    {
        try {
            return $this->frequentlyAskedQuestionRepository->find($id);
        } catch (Throwable) {
            return null;
        }
    }

    public function loadByRemoteId(int|string $remoteId): ?ResourceInterface
    {
        return $this->load($remoteId);
    }
}
