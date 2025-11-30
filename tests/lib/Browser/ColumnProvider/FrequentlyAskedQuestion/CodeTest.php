<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\ColumnProvider\FrequentlyAskedQuestion;

use Netgen\Layouts\Sylius\BitBag\Browser\ColumnProvider\FrequentlyAskedQuestion\Code;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\FrequentlyAskedQuestion\Item as FrequentlyAskedQuestionItem;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\FrequentlyAskedQuestion as FrequentlyAskedQuestionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Code::class)]
final class CodeTest extends TestCase
{
    private Code $codeColumn;

    protected function setUp(): void
    {
        $this->codeColumn = new Code();
    }

    public function testGetValue(): void
    {
        $frequentlyAskedQuestion = new FrequentlyAskedQuestionStub(5, 'TEST_QUESTION');
        $item = new FrequentlyAskedQuestionItem($frequentlyAskedQuestion);

        self::assertSame('TEST_QUESTION', $this->codeColumn->getValue($item));
    }
}
