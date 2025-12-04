<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\BitBag\Item\ValueLoader\FrequentlyAskedQuestionValueLoader;
use Netgen\Layouts\Sylius\BitBag\Repository\FrequentlyAskedQuestionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\FrequentlyAskedQuestion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(FrequentlyAskedQuestionValueLoader::class)]
final class FrequentlyAskedQuestionValueLoaderTest extends TestCase
{
    private Stub&FrequentlyAskedQuestionRepositoryInterface $frequentlyAskedQuestionRepositoryStub;

    private FrequentlyAskedQuestionValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->frequentlyAskedQuestionRepositoryStub = self::createStub(FrequentlyAskedQuestionRepositoryInterface::class);
        $this->valueLoader = new FrequentlyAskedQuestionValueLoader($this->frequentlyAskedQuestionRepositoryStub);
    }

    public function testLoad(): void
    {
        $frequentlyAskedQuestion = new FrequentlyAskedQuestion(42, 'TEST_QUESTION');

        $this->frequentlyAskedQuestionRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($frequentlyAskedQuestion);

        self::assertSame($frequentlyAskedQuestion, $this->valueLoader->load(42));
    }

    public function testLoadWithNoFrequentlyAskedQuestion(): void
    {
        $this->frequentlyAskedQuestionRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->frequentlyAskedQuestionRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $frequentlyAskedQuestion = new FrequentlyAskedQuestion(42, 'TEST_QUESTION');

        $this->frequentlyAskedQuestionRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($frequentlyAskedQuestion);

        self::assertSame($frequentlyAskedQuestion, $this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithNoFrequentlyAskedQuestion(): void
    {
        $this->frequentlyAskedQuestionRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->frequentlyAskedQuestionRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }
}
