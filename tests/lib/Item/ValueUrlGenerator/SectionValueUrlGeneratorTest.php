<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\BitBag\Item\ValueUrlGenerator\SectionValueUrlGenerator;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(SectionValueUrlGenerator::class)]
final class SectionValueUrlGeneratorTest extends TestCase
{
    private MockObject&UrlGeneratorInterface $urlGeneratorMock;

    private SectionValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator = new SectionValueUrlGenerator($this->urlGeneratorMock);
    }

    public function testGenerateDefaultUrl(): void
    {
        $this->urlGeneratorMock
            ->expects($this->once())
            ->method('generate')
            ->with(
                self::identicalTo('bitbag_sylius_cms_plugin_shop_page_index_by_section_code'),
                self::identicalTo(['sectionCode' => 'blog']),
            )
            ->willReturn('/en_GB/pages/blog');

        self::assertSame(
            '/en_GB/pages/blog',
            $this->urlGenerator->generateDefaultUrl(new Section(42, 'blog', 'Blog')),
        );
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorMock
            ->expects($this->once())
            ->method('generate')
            ->with(
                self::identicalTo('bitbag_sylius_cms_plugin_admin_section_update'),
                self::identicalTo(['id' => 42]),
            )
            ->willReturn('/admin/sections/42/edit');

        self::assertSame(
            '/admin/sections/42/edit',
            $this->urlGenerator->generateAdminUrl(new Section(42, 'blog', 'Blog')),
        );
    }
}
