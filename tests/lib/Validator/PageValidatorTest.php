<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Validator;

use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Page as PageStub;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint\Page;
use Netgen\Layouts\Sylius\BitBag\Validator\PageValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(PageValidator::class)]
final class PageValidatorTest extends ValidatorTestCase
{
    private MockObject&PageRepositoryInterface $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Page();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->repositoryMock = $this->createMock(PageRepositoryInterface::class);

        return new PageValidator($this->repositoryMock);
    }

    public function testValidateValid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new PageStub(42, 'contact-us'));

        $this->assertValid(true, 42);
    }

    public function testValidateNull(): void
    {
        $this->repositoryMock
            ->expects($this->never())
            ->method('find');

        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $this->assertValid(false, 42);
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\Layouts\Sylius\BitBag\Validator\Constraint\Page", "Symfony\Component\Validator\Constraints\NotBlank" given');

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
