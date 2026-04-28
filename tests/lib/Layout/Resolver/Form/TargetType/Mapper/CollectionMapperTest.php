<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Sylius\Cms\Layout\Resolver\Form\TargetType\Mapper\CollectionMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CollectionMapper::class)]
final class CollectionMapperTest extends TestCase
{
    private CollectionMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new CollectionMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserIntegerType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_cms_collection',
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
