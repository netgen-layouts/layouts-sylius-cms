<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\ContentBrowser\Item\FrequentlyAskedQuestion;

use Netgen\Layouts\Sylius\Cms\Browser\Item\FrequentlyAskedQuestion\Item;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\FrequentlyAskedQuestion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\FrequentlyAskedQuestionInterface;

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
