<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\BitBag\Parameters\Form\Mapper\SectionMapper;
use Netgen\Layouts\Sylius\BitBag\Parameters\ParameterType\SectionType;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SectionMapper::class)]
final class SectionMapperTest extends TestCase
{
    private SectionMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SectionMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserIntegerType::class, $this->mapper->getFormType());
    }

    public function testMapOptions(): void
    {
        $parameterDefinition = ParameterDefinition::fromArray(
            [
                'type' => new SectionType(self::createStub(SectionRepositoryInterface::class)),
                'isRequired' => false,
            ],
        );

        self::assertSame(
            [
                'item_type' => 'bitbag_section',
                'required' => false,
            ],
            $this->mapper->mapOptions($parameterDefinition),
        );
    }
}
