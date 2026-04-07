<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\Item\FrequentlyAskedQuestion;

use Sylius\CmsPlugin\Entity\FrequentlyAskedQuestionInterface as SyliusCmsFrequentlyAskedQuestionInterface;

interface FrequentlyAskedQuestionInterface
{
    /**
     * Returns the Sylius CMS frequently asked question.
     */
    public SyliusCmsFrequentlyAskedQuestionInterface $frequentlyAskedQuestion { get; }
}
