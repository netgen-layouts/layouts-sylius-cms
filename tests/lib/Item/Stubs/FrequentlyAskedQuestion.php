<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs;

use BitBag\SyliusCmsPlugin\Entity\FrequentlyAskedQuestion as BaseFrequentlyAskedQuestion;

final class FrequentlyAskedQuestion extends BaseFrequentlyAskedQuestion
{
    public function __construct(
        int $id,
        string $code,
        ?string $question = null,
        ?string $answer = null,
        bool $enabled = true,
    ) {
        parent::__construct();

        $this->id = $id;
        $this->setCode($code);
        $this->setEnabled($enabled);

        $this->setCurrentLocale('en');
        $this->setFallbackLocale('en');

        if ($question !== null) {
            $this->setQuestion($question);
        }

        if ($answer !== null) {
            $this->setAnswer($answer);
        }
    }
}
