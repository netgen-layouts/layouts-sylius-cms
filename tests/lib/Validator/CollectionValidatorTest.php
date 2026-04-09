<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Validator;

use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Collection as CollectionStub;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Collection;
use Netgen\Layouts\Sylius\Cms\Validator\CollectionValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(CollectionValidator::class)]
final class CollectionValidatorTest extends ValidatorTestCase
{
    private Stub&CollectionRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Collection();
    }

    public function testValidateValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(new CollectionStub(42, 'blog'));

        $this->assertValid(true, 42);
    }

    public function testValidateNull(): void
    {
        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        $this->assertValid(false, 42);
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\Layouts\Sylius\Cms\Validator\Constraint\Collection", "Symfony\Component\Validator\Constraints\NotBlank" given');

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "int", "array" given');

        $this->assertValid(true, []);
    }

    protected function getConstraintValidator(): ConstraintValidatorInterface
    {
        $this->repositoryStub = self::createStub(CollectionRepositoryInterface::class);

        return new CollectionValidator($this->repositoryStub);
    }
}
