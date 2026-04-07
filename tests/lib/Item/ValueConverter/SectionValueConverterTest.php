<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Cms\Item\ValueConverter\SectionValueConverter;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Section as SectionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\Page;
use Sylius\CmsPlugin\Entity\Section;

#[CoversClass(SectionValueConverter::class)]
final class SectionValueConverterTest extends TestCase
{
    private SectionValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new SectionValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Section()));
        self::assertFalse($this->valueConverter->supports(new Page()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_cms_section',
            $this->valueConverter->getValueType(
                new Section(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new SectionStub(42, 'blog', 'Blog'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new SectionStub(42, 'blog', 'Blog'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'Blog',
            $this->valueConverter->getName(
                new SectionStub(42, 'blog', 'Blog'),
            ),
        );
    }

    public function testGetObject(): void
    {
        $section = new SectionStub(42, 'blog', 'Blog');

        self::assertSame($section, $this->valueConverter->getObject($section));
    }
}
