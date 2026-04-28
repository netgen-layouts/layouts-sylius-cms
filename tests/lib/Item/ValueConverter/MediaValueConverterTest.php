<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Cms\Item\ValueConverter\MediaValueConverter;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Media as MediaStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\Collection;
use Sylius\CmsPlugin\Entity\Media;

#[CoversClass(MediaValueConverter::class)]
final class MediaValueConverterTest extends TestCase
{
    private MediaValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new MediaValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Media()));
        self::assertFalse($this->valueConverter->supports(new Collection()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_cms_media',
            $this->valueConverter->getValueType(
                new Media(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new MediaStub(42, 'logo-image', 'Logo image'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new MediaStub(42, 'logo-image', 'Logo image'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'Logo image',
            $this->valueConverter->getName(
                new MediaStub(42, 'logo-image', 'Logo image'),
            ),
        );
    }

    public function testGetNameWithEmptyName(): void
    {
        self::assertSame(
            'logo-image',
            $this->valueConverter->getName(
                new MediaStub(42, 'logo-image'),
            ),
        );
    }

    public function testGetNameWithEmptyNameAndCode(): void
    {
        self::assertSame(
            '',
            $this->valueConverter->getName(
                new MediaStub(42, ''),
            ),
        );
    }

    public function testGetIsVisible(): void
    {
        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new MediaStub(42, 'logo-image', 'Logo image'),
            ),
        );

        self::assertFalse(
            $this->valueConverter->getIsVisible(
                new MediaStub(42, 'logo-image', 'Logo image', 'file', 'image/png', false),
            ),
        );
    }

    public function testGetObject(): void
    {
        $media = new MediaStub(42, 'logo-image', 'Logo image');

        self::assertSame($media, $this->valueConverter->getObject($media));
    }
}
