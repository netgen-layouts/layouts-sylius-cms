<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\BitBag\Item\ValueUrlGenerator\MediaValueUrlGenerator;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Media;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(MediaValueUrlGenerator::class)]
final class MediaValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private MediaValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new MediaValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('bitbag_sylius_cms_plugin_admin_media_update'),
                self::identicalTo(['id' => 42]),
            )
            ->willReturn('/admin/media/42/edit');

        self::assertSame(
            '/admin/media/42/edit',
            $this->urlGenerator->generateAdminUrl(new Media(42, 'logo-image', 'Logo')),
        );
    }
}
