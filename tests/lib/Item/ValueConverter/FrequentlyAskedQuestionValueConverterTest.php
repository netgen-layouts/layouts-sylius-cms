<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Cms\Item\ValueConverter\FrequentlyAskedQuestionValueConverter;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\FrequentlyAskedQuestion as FrequentlyAskedQuestionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\FrequentlyAskedQuestion;
use Sylius\CmsPlugin\Entity\Section;

#[CoversClass(FrequentlyAskedQuestionValueConverter::class)]
final class FrequentlyAskedQuestionValueConverterTest extends TestCase
{
    private FrequentlyAskedQuestionValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new FrequentlyAskedQuestionValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new FrequentlyAskedQuestion()));
        self::assertFalse($this->valueConverter->supports(new Section()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_cms_frequently_asked_question',
            $this->valueConverter->getValueType(
                new FrequentlyAskedQuestion(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new FrequentlyAskedQuestionStub(42, 'TEST_QUESTION'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new FrequentlyAskedQuestionStub(42, 'TEST_QUESTION'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'What is this?',
            $this->valueConverter->getName(
                new FrequentlyAskedQuestionStub(42, 'TEST_QUESTION', 'What is this?'),
            ),
        );
    }

    public function testGetIsVisible(): void
    {
        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new FrequentlyAskedQuestionStub(42, 'TEST_QUESTION'),
            ),
        );

        self::assertFalse(
            $this->valueConverter->getIsVisible(
                new FrequentlyAskedQuestionStub(42, 'TEST_QUESTION', null, null, false),
            ),
        );
    }

    public function testGetObject(): void
    {
        $frequentlyAskedQuestion = new FrequentlyAskedQuestionStub(42, 'TEST_QUESTION');

        self::assertSame($frequentlyAskedQuestion, $this->valueConverter->getObject($frequentlyAskedQuestion));
    }
}
