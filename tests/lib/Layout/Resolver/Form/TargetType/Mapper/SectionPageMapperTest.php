<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Sylius\BitBag\Layout\Resolver\Form\TargetType\Mapper\SectionPageMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SectionPageMapper::class)]
final class SectionPageMapperTest extends TestCase
{
    private SectionPageMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SectionPageMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserIntegerType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'bitbag_section',
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
