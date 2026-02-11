<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\BitBag\Item\ValueUrlGenerator\SectionValueUrlGenerator;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(SectionValueUrlGenerator::class)]
final class SectionValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private SectionValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new SectionValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateDefaultUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->willReturn('/en_GB/pages/blog');

        self::assertSame(
            '/en_GB/pages/blog',
            $this->urlGenerator->generateDefaultUrl(new Section(42, 'blog', 'Blog')),
        );
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->willReturn('/admin/sections/42/edit');

        self::assertSame(
            '/admin/sections/42/edit',
            $this->urlGenerator->generateAdminUrl(new Section(42, 'blog', 'Blog')),
        );
    }
}
