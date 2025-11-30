<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\ContentBrowser\ColumnProvider\Page;

use Netgen\Layouts\Sylius\BitBag\Browser\ColumnProvider\Page\Code;
use Netgen\Layouts\Sylius\BitBag\Browser\Item\Page\Item as PageItem;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Page as PageStub;
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
        $page = new PageStub(5, 'ABOUT_US', 'About us');
        $item = new PageItem($page);

        self::assertSame('ABOUT_US', $this->codeColumn->getValue($item));
    }
}
