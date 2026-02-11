<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\BitBag\Item\ValueUrlGenerator\FrequentlyAskedQuestionValueUrlGenerator;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\FrequentlyAskedQuestion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(FrequentlyAskedQuestionValueUrlGenerator::class)]
final class FrequentlyAskedQuestionValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private FrequentlyAskedQuestionValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new FrequentlyAskedQuestionValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->willReturn('/admin/faq/42/edit');

        self::assertSame(
            '/admin/faq/42/edit',
            $this->urlGenerator->generateAdminUrl(new FrequentlyAskedQuestion(42, 'TEST_QUESTION')),
        );
    }
}
