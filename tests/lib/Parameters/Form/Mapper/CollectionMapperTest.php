<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Cms\Parameters\Form\Mapper\CollectionMapper;
use Netgen\Layouts\Sylius\Cms\Parameters\ParameterType\CollectionType;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
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

    public function testMapOptions(): void
    {
        $parameterDefinition = ParameterDefinition::fromArray(
            [
                'type' => new CollectionType(self::createStub(CollectionRepositoryInterface::class)),
                'isRequired' => false,
            ],
        );

        self::assertSame(
            [
                'item_type' => 'sylius_cms_collection',
                'required' => false,
            ],
            $this->mapper->mapOptions($parameterDefinition),
        );
    }
}
