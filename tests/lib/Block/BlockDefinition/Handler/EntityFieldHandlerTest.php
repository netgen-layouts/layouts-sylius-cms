<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Block\BlockDefinition\Handler;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\DynamicParameters;
use Netgen\Layouts\Parameters\Parameter;
use Netgen\Layouts\Parameters\ParameterList;
use Netgen\Layouts\Sylius\BitBag\Block\BlockDefinition\Handler\BitBagEntityField;
use Netgen\Layouts\Sylius\BitBag\Block\BlockDefinition\Handler\EntityFieldHandler;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Page as PageStub;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section as SectionStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(EntityFieldHandler::class)]
final class EntityFieldHandlerTest extends TestCase
{
    private MockObject&RequestStack $requestStackMock;

    private EntityFieldHandler $handler;

    protected function setUp(): void
    {
        $this->requestStackMock = $this->createMock(RequestStack::class);

        $this->handler = new EntityFieldHandler($this->requestStackMock);
    }

    public function testIsContextual(): void
    {
        self::assertTrue($this->handler->isContextual(new Block()));
    }

    public function testGetDynamicParametersWithPage(): void
    {
        $page = new PageStub(5, 'about-us');
        $page->setCurrentLocale('nor_NO');
        $page->setName('About us');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_bitbag_page', $page);

        $this->requestStackMock
            ->expects(self::once())
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

        $field = BitBagEntityField::fromBitBagEntity($page, 'name');

        self::assertSame($field->isEmpty(), $params['field']->isEmpty());
        self::assertSame($field->getType(), $params['field']->getType());
        self::assertSame($field->getValue(), $params['field']->getValue());
    }

    public function testGetDynamicParametersWithSection(): void
    {
        $section = new SectionStub(5, 'blog');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_bitbag_section', $section);

        $this->requestStackMock
            ->expects(self::once())
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

        $field = BitBagEntityField::fromBitBagEntity($section, 'code');

        self::assertSame($field->isEmpty(), $params['field']->isEmpty());
        self::assertSame($field->getType(), $params['field']->getType());
        self::assertSame($field->getValue(), $params['field']->getValue());
    }

    public function testGetDynamicParametersWithoutRequest(): void
    {
        $this->requestStackMock
            ->expects(self::once())
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
