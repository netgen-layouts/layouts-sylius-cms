<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Browser\ColumnProvider\Collection;

use Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Collection\Code;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Collection\Item as CollectionItem;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Collection as CollectionStub;
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
        $collection = new CollectionStub(5, 'BLOG', 'Blog');
        $item = new CollectionItem($collection);

        self::assertSame('BLOG', $this->codeColumn->getValue($item));
    }
}
