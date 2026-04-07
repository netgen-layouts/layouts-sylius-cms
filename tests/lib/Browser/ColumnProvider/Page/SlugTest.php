<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\ContentBrowser\ColumnProvider\Page;

use Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\Page\Slug;
use Netgen\Layouts\Sylius\Cms\Browser\Item\Page\Item as PageItem;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Page as PageStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Slug::class)]
final class SlugTest extends TestCase
{
    private Slug $slugColumn;

    protected function setUp(): void
    {
        $this->slugColumn = new Slug();
    }

    public function testGetValue(): void
    {
        $page = new PageStub(5, 'ABOUT_US', 'About us', 'about-us');
        $item = new PageItem($page);

        self::assertSame('about-us', $this->slugColumn->getValue($item));
    }
}
