<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\ContentBrowser\ColumnProvider\Media;

use Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Media\Type;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Media\Item as MediaItem;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Media as MediaStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Type::class)]
final class TypeTest extends TestCase
{
    private Type $typeColumn;

    protected function setUp(): void
    {
        $this->typeColumn = new Type();
    }

    public function testGetValue(): void
    {
        $media = new MediaStub(5, 'LOGO', 'Logo image', 'image');
        $item = new MediaItem($media);

        self::assertSame('image', $this->typeColumn->getValue($item));
    }
}
