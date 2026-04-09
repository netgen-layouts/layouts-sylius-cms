<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Block\BlockDefinition\Handler;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\DynamicParameters;
use Netgen\Layouts\Parameters\Parameter;
use Netgen\Layouts\Parameters\ParameterList;
use Netgen\Layouts\Sylius\Cms\Block\BlockDefinition\Handler\EntityField;
use Netgen\Layouts\Sylius\Cms\Block\BlockDefinition\Handler\EntityFieldHandler;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Page as PageStub;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Collection as CollectionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(EntityFieldHandler::class)]
final class EntityFieldHandlerTest extends TestCase
{
    private Stub&RequestStack $requestStackStub;

    private EntityFieldHandler $handler;

    protected function setUp(): void
    {
        $this->requestStackStub = self::createStub(RequestStack::class);

        $this->handler = new EntityFieldHandler($this->requestStackStub);
    }

    public function testGetDynamicParametersWithPage(): void
    {
        $page = new PageStub(5, 'about-us');
        $page->setCurrentLocale('nor_NO');
        $page->setName('About us');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_cms_page', $page);

        $this->requestStackStub
            ->method('getCurrentRequest')
            ->willReturn($request);

        $params = new DynamicParameters();

        $this->handler->getDynamicParameters(
            $params,
            Block::fromArray(
                [
                    'parameters' => new ParameterList(
                        [
                            'field_identifier' => Parameter::fromArray(
                                [
                                    'name' => 'field_identifier',
                                    'value' => 'name',
                                ],
                            ),
                        ],
                    ),
                ],
            ),
        );

        $field = EntityField::fromEntity($page, 'name');

        self::assertSame($field->isEmpty(), $params['field']->isEmpty());
        self::assertSame($field->type, $params['field']->type);
        self::assertSame($field->value, $params['field']->value);
    }

    public function testGetDynamicParametersWithCollection(): void
    {
        $collection = new CollectionStub(5, 'blog');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_cms_collection', $collection);

        $this->requestStackStub
            ->method('getCurrentRequest')
            ->willReturn($request);

        $params = new DynamicParameters();

        $this->handler->getDynamicParameters(
            $params,
            Block::fromArray(
                [
                    'parameters' => new ParameterList(
                        [
                            'field_identifier' => Parameter::fromArray(
                                [
                                    'name' => 'field_identifier',
                                    'value' => 'code',
                                ],
                            ),
                        ],
                    ),
                ],
            ),
        );

        $field = EntityField::fromEntity($collection, 'code');

        self::assertSame($field->isEmpty(), $params['field']->isEmpty());
        self::assertSame($field->type, $params['field']->type);
        self::assertSame($field->value, $params['field']->value);
    }

    public function testGetDynamicParametersWithoutRequest(): void
    {
        $this->requestStackStub
            ->method('getCurrentRequest')
            ->willReturn(null);

        $params = new DynamicParameters();

        $this->handler->getDynamicParameters(
            $params,
            Block::fromArray(
                [
                    'parameters' => new ParameterList(
                        [
                            'field_identifier' => Parameter::fromArray(
                                [
                                    'name' => 'field_identifier',
                                    'value' => 'code',
                                ],
                            ),
                        ],
                    ),
                ],
            ),
        );

        self::assertNull($params['field']);
    }
}
