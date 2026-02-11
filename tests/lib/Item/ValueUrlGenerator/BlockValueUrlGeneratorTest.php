<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\BitBag\Item\ValueUrlGenerator\BlockValueUrlGenerator;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Block;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(BlockValueUrlGenerator::class)]
final class BlockValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private BlockValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new BlockValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->willReturn('/blocks/42/edit');

        self::assertSame(
            '/blocks/42/edit',
            $this->urlGenerator->generateAdminUrl(new Block(42, 'header', 'Header')),
        );
    }
}
