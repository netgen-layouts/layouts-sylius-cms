<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\Item\FrequentlyAskedQuestion;

use BitBag\SyliusCmsPlugin\Entity\FrequentlyAskedQuestionInterface;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\FrequentlyAskedQuestion\Item;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\FrequentlyAskedQuestion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    private FrequentlyAskedQuestionInterface $frequentlyAskedQuestion;

    private Item $item;

    protected function setUp(): void
    {
        $this->frequentlyAskedQuestion = new FrequentlyAskedQuestion(42, 'TEST_QUESTION');
        $this->frequentlyAskedQuestion->setCurrentLocale('en');
        $this->frequentlyAskedQuestion->setFallbackLocale('en');
        $this->frequentlyAskedQuestion->setQuestion('What is this?');
        $this->frequentlyAskedQuestion->setAnswer('This is a test.');

        $this->item = new Item($this->frequentlyAskedQuestion);
    }

    public function testGetValue(): void
    {
        self::assertSame(42, $this->item->value);
    }

    public function testGetName(): void
    {
        self::assertSame('What is this?', $this->item->name);
    }

    public function testGetFrequentlyAskedQuestion(): void
    {
        self::assertSame($this->frequentlyAskedQuestion, $this->item->frequentlyAskedQuestion);
    }
}
