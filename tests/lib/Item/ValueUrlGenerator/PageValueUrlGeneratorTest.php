<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\BitBag\Item\ValueUrlGenerator\PageValueUrlGenerator;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(PageValueUrlGenerator::class)]
final class PageValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private PageValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new PageValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateDefaultUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('bitbag_sylius_cms_plugin_shop_page_show'),
                self::identicalTo(['slug' => 'about-us']),
            )
            ->willReturn('/en_GB/page/about-us');

        self::assertSame(
            '/en_GB/page/about-us',
            $this->urlGenerator->generateDefaultUrl(new Page(42, 'about-us', 'About us', 'about-us')),
        );
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('bitbag_sylius_cms_plugin_admin_page_update'),
                self::identicalTo(['id' => 42]),
            )
            ->willReturn('/admin/pages/42/edit');

        self::assertSame(
            '/admin/pages/42/edit',
            $this->urlGenerator->generateAdminUrl(new Page(42, 'about-us', 'About us', 'about-us')),
        );
    }
}
