<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\FrequentlyAskedQuestion;

use Netgen\ContentBrowser\Item\ItemInterface;
use Sylius\CmsPlugin\Entity\FrequentlyAskedQuestionInterface as SyliusCmsFrequentlyAskedQuestionInterface;

final class Item implements ItemInterface, FrequentlyAskedQuestionInterface
{
    public int $value {
        get => $this->frequentlyAskedQuestion->getId();
    }

    public string $name {
        get => (string) $this->frequentlyAskedQuestion->getQuestion();
    }

    public true $isVisible {
        get => true;
    }

    public true $isSelectable {
        get => true;
    }

    public function __construct(
        public private(set) SyliusCmsFrequentlyAskedQuestionInterface $frequentlyAskedQuestion,
    ) {}
}
