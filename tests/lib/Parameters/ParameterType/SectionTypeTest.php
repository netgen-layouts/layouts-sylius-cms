<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\BitBag\Parameters\ParameterType\SectionType;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section as SectionStub;
use Netgen\Layouts\Sylius\BitBag\Tests\TestCase\ValidatorTestCaseTrait;
use Netgen\Layouts\Tests\Parameters\ParameterType\ParameterTypeTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

#[CoversClass(SectionType::class)]
final class SectionTypeTest extends TestCase
{
    use ParameterTypeTestTrait;

    use ValidatorTestCaseTrait;

    private Stub&SectionRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(SectionRepositoryInterface::class);

        $this->type = new SectionType($this->repositoryStub);
    }

    public function testGetIdentifier(): void
    {
        self::assertSame('bitbag_section', $this->type::getIdentifier());
    }

    public function testFromHash(): void
    {
        self::assertSame(42, $this->type->fromHash(new ParameterDefinition(), '42'));
    }

    public function testFromHashWithNullValue(): void
    {
        self::assertNull($this->type->fromHash(new ParameterDefinition(), null));
    }

    /**
     * @param mixed[] $options
     * @param mixed[] $resolvedOptions
     */
    #[DataProvider('validOptionsDataProvider')]
    public function testValidOptions(array $options, array $resolvedOptions): void
    {
        $parameterDefinition = $this->getParameterDefinition($options);
        self::assertSame($resolvedOptions, $parameterDefinition->options);
    }

    /**
     * @param mixed[] $options
     */
    #[DataProvider('invalidOptionsDataProvider')]
    public function testInvalidOptions(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getParameterDefinition($options);
    }

    /**
     * @return iterable<mixed>
     */
    public static function validOptionsDataProvider(): iterable
    {
        return [
            [
                [],
                [],
            ],
        ];
    }

    /**
     * @return iterable<mixed>
     */
    public static function invalidOptionsDataProvider(): iterable
    {
        return [
            [
                [
                    'undefined_value' => 'Value',
                ],
            ],
        ];
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new SectionStub(42, 'blog'));

        $validator = $this->createValidator($this->repositoryStub);

        $parameterDefinition = $this->getParameterDefinition([], true);

        $errors = $validator->validate(42, $this->type->getConstraints($parameterDefinition, 42));
        self::assertCount(0, $errors);
    }

    public function testValidationValidWithNonRequiredValue(): void
    {
        $validator = $this->createValidator($this->repositoryStub);

        $parameterDefinition = $this->getParameterDefinition();

        $errors = $validator->validate(null, $this->type->getConstraints($parameterDefinition, null));
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $parameterDefinition = $this->getParameterDefinition([], true);

        $errors = $validator->validate(42, $this->type->getConstraints($parameterDefinition, 42));
        self::assertNotCount(0, $errors);
    }

    #[DataProvider('emptyDataProvider')]
    public function testIsValueEmpty(mixed $value, bool $isEmpty): void
    {
        self::assertSame($isEmpty, $this->type->isValueEmpty(new ParameterDefinition(), $value));
    }

    /**
     * @return iterable<mixed>
     */
    public static function emptyDataProvider(): iterable
    {
        return [
            [null, true],
            [new SectionStub(42, 'blog'), false],
        ];
    }

    public function testGetValueObject(): void
    {
        $section = new SectionStub(42, 'blog');

        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($section);

        /** @var \Netgen\Layouts\Sylius\BitBag\Parameters\ParameterType\SectionType $type */
        $type = $this->type;

        self::assertSame($section, $type->getValueObject(42));
    }

    public function testGetValueObjectWithNoSection(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        /** @var \Netgen\Layouts\Sylius\BitBag\Parameters\ParameterType\SectionType $type */
        $type = $this->type;

        self::assertNull($type->getValueObject(42));
    }
}
