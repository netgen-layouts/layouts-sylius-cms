<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Browser\Item\FrequentlyAskedQuestion;

use BitBag\SyliusCmsPlugin\Entity\FrequentlyAskedQuestionInterface as BitBagFrequentlyAskedQuestionInterface;
use Netgen\ContentBrowser\Item\ItemInterface;

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
        public private(set) BitBagFrequentlyAskedQuestionInterface $frequentlyAskedQuestion,
    ) {}
}
