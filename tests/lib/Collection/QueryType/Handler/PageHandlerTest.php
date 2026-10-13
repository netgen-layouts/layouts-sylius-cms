<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Collection\QueryType\Handler;

use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Parameters\Parameter;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterList;
use Netgen\Layouts\Sylius\Cms\Collection\QueryType\Handler\PageHandler;
use Netgen\Layouts\Sylius\Cms\Repository\PageRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

#[CoversClass(PageHandler::class)]
final class PageHandlerTest extends TestCase
{
    private PageHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new PageHandler(
            self::createStub(PageRepositoryInterface::class),
            self::createStub(LocaleContextInterface::class),
            new RequestStack(),
        );
    }

    public function testBuildParametersOffersNoProductFilter(): void
    {
        $names = [];

        $builderStub = self::createStub(ParameterBuilderInterface::class);
        $builderStub->method('get')->willReturnSelf();
        $builderStub->method('add')->willReturnCallback(
            static function (string $name) use (&$names, $builderStub): ParameterBuilderInterface {
                $names[] = $name;

                return $builderStub;
            },
        );

        $this->handler->buildParameters($builderStub);

        self::assertSame(
            ['use_current_collection', 'collection_id', 'filter_by_channel', 'channels', 'channels_filter', 'sort_type', 'sort_direction'],
            $names,
        );
    }

    public function testIsContextualFollowsTheCollectionParameterOnly(): void
    {
        self::assertTrue($this->handler->isContextual($this->query(['use_current_collection' => true])));
        self::assertFalse($this->handler->isContextual($this->query(['use_current_collection' => false])));
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function query(array $parameters): Query
    {
        $list = [];
        foreach ($parameters as $name => $value) {
            $list[$name] = Parameter::fromArray(['name' => $name, 'value' => $value, 'isEmpty' => $value === null]);
        }

        return Query::fromArray(['id' => Uuid::v4(), 'parameters' => new ParameterList($list)]);
    }
}
