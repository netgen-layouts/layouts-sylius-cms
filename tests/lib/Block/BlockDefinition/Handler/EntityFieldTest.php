<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Block\BlockDefinition\Handler;

use Netgen\Layouts\Sylius\Cms\Block\BlockDefinition\Handler\EntityField;
use Netgen\Layouts\Sylius\Cms\Block\BlockDefinition\Handler\EntityFieldType;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Block;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Collection;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Media;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EntityField::class)]
final class EntityFieldTest extends TestCase
{
    public function testFromEntityWithStringField(): void
    {
        $page = new Page(1, 'contact', 'Contact us');

        $field = EntityField::fromEntity($page, 'name');

        self::assertSame('Contact us', $field->value);
        self::assertSame(EntityFieldType::String, $field->type);
        self::assertFalse($field->isEmpty());
    }

    public function testFromEntityWithUnknownField(): void
    {
        $page = new Page(1, 'contact', 'Contact us');

        $field = EntityField::fromEntity($page, 'nonexistent');

        self::assertNull($field->value);
        self::assertSame(EntityFieldType::Other, $field->type);
        self::assertTrue($field->isEmpty());
    }

    public function testFromEntityWithBooleanField(): void
    {
        $block = new Block(1, 'header', 'Header', true);

        $field = EntityField::fromEntity($block, 'enabled');

        self::assertTrue($field->value);
        self::assertSame(EntityFieldType::Boolean, $field->type);
    }

    public function testFromEntityWithContentFieldOnPage(): void
    {
        $page = new Page(1, 'contact', 'Contact');

        $field = EntityField::fromEntity($page, 'content');

        self::assertSame($page, $field->value);
        self::assertSame(EntityFieldType::ContentElements, $field->type);
    }

    public function testFromEntityWithContentFieldOnBlock(): void
    {
        $block = new Block(1, 'header', 'Header');

        $field = EntityField::fromEntity($block, 'content');

        self::assertSame($block, $field->value);
        self::assertSame(EntityFieldType::ContentElements, $field->type);
    }

    public function testFromEntityWithContentFieldOnMedia(): void
    {
        $media = new Media(1, 'banner');

        $field = EntityField::fromEntity($media, 'content');

        self::assertSame($media, $field->value);
        self::assertSame(EntityFieldType::Media, $field->type);
    }

    public function testFromEntityWithUnknownFieldOnCollection(): void
    {
        $collection = new Collection(1, 'blog', 'Blog');

        $field = EntityField::fromEntity($collection, 'nonexistent');

        self::assertNull($field->value);
        self::assertSame(EntityFieldType::Other, $field->type);
    }
}
