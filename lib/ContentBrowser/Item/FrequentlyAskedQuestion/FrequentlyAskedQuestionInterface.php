<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\FrequentlyAskedQuestion;

use BitBag\SyliusCmsPlugin\Entity\FrequentlyAskedQuestionInterface as BitBagFrequentlyAskedQuestionInterface;

interface FrequentlyAskedQuestionInterface
{
    /**
     * Returns the BitBag frequently asked question.
     */
    public BitBagFrequentlyAskedQuestionInterface $frequentlyAskedQuestion { get; }
}
