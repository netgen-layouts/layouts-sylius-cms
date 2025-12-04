<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Validator;

use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section as SectionStub;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint\Section;
use Netgen\Layouts\Sylius\BitBag\Validator\SectionValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(SectionValidator::class)]
final class SectionValidatorTest extends ValidatorTestCase
{
    private Stub&SectionRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Section();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->repositoryStub = self::createStub(SectionRepositoryInterface::class);

        return new SectionValidator($this->repositoryStub);
    }

    public function testValidateValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new SectionStub(42, 'blog'));

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
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $this->assertValid(false, 42);
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\Layouts\Sylius\BitBag\Validator\Constraint\Section", "Symfony\Component\Validator\Constraints\NotBlank" given');

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "scalar", "array" given');

        $this->assertValid(true, []);
    }
}
