<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueConverter;

use BitBag\SyliusCmsPlugin\Entity\Page;
use BitBag\SyliusCmsPlugin\Entity\Section;
use Netgen\Layouts\Sylius\BitBag\Item\ValueConverter\SectionValueConverter;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Section as SectionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

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
            'bitbag_section',
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
