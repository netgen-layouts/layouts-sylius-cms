<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Browser\ColumnProvider\Media;

use Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Media\MimeType;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Media\Item as MediaItem;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Media as MediaStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MimeType::class)]
final class MimeTypeTest extends TestCase
{
    private MimeType $mimeTypeColumn;

    protected function setUp(): void
    {
        $this->mimeTypeColumn = new MimeType();
    }

    public function testGetValue(): void
    {
        $media = new MediaStub(5, 'LOGO', 'Logo image', 'image', 'image/png');
        $item = new MediaItem($media);

        self::assertSame('image/png', $this->mimeTypeColumn->getValue($item));
    }
}
