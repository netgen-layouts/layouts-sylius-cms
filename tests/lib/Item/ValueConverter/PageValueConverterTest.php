<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Cms\Item\ValueConverter\PageValueConverter;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Page as PageStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\Page;
use Sylius\CmsPlugin\Entity\Section;

#[CoversClass(PageValueConverter::class)]
final class PageValueConverterTest extends TestCase
{
    private PageValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new PageValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Page()));
        self::assertFalse($this->valueConverter->supports(new Section()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_cms_page',
            $this->valueConverter->getValueType(
                new Page(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new PageStub(42, 'about-us', 'About us'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new PageStub(42, 'about-us', 'About us'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'About us',
            $this->valueConverter->getName(
                new PageStub(42, 'about-us', 'About us'),
            ),
        );
    }

    public function testGetIsVisible(): void
    {
        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new PageStub(42, 'about-us', 'About us'),
            ),
        );

        self::assertFalse(
            $this->valueConverter->getIsVisible(
                new PageStub(42, 'about-us', 'About us', null, false),
            ),
        );
    }

    public function testGetObject(): void
    {
        $page = new PageStub(42, 'about-us', 'About us');

        self::assertSame($page, $this->valueConverter->getObject($page));
    }
}
