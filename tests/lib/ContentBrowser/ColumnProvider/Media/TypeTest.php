<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\ColumnProvider\Media;

use Netgen\Layouts\Sylius\BitBag\ContentBrowser\ColumnProvider\Media\Type;
use Netgen\Layouts\Sylius\BitBag\ContentBrowser\Item\Media\Item as MediaItem;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Media as MediaStub;
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
