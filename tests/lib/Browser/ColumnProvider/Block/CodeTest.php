<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Browser\ColumnProvider\Block;

use Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Block\Code;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Block\Item as BlockItem;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Block as BlockStub;
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
        $block = new BlockStub(5, 'FOOTER', 'Footer block');
        $item = new BlockItem($block);

        self::assertSame('FOOTER', $this->codeColumn->getValue($item));
    }
}
