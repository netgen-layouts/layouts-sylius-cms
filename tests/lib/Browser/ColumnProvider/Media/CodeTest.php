<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Browser\ColumnProvider\Media;

use Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Media\Code;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Media\Item as MediaItem;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Media as MediaStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Code::class)]
final class CodeTest extends TestCase
{
    private Code $codeColumn;

    protected function setUp(): void
    {
        $this->codeColumn = new Code();
    }

    public function testGetValue(): void
    {
        $media = new MediaStub(5, 'LOGO', 'Logo image');
        $item = new MediaItem($media);

        self::assertSame('LOGO', $this->codeColumn->getValue($item));
    }
}
