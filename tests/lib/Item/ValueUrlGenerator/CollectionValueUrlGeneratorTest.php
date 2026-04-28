<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\Cms\Item\ValueUrlGenerator\CollectionValueUrlGenerator;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(CollectionValueUrlGenerator::class)]
final class CollectionValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private CollectionValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new CollectionValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateDefaultUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->willReturn('/en_GB/pages/blog');

        self::assertSame(
            '/en_GB/pages/blog',
            $this->urlGenerator->generateDefaultUrl(new Collection(42, 'blog', 'Blog')),
        );
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->willReturn('/admin/collections/42/edit');

        self::assertSame(
            '/admin/collections/42/edit',
            $this->urlGenerator->generateAdminUrl(new Collection(42, 'blog', 'Blog')),
        );
    }
}
