<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetType\Section;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section as SectionStub;
use Netgen\Layouts\Sylius\BitBag\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

#[CoversClass(Section::class)]
final class SectionTest extends TestCase
{
    private Stub&SectionRepositoryInterface $repositoryStub;

    private Section $targetType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(SectionRepositoryInterface::class);

        $this->targetType = new Section($this->repositoryStub);
    }

    public function testGetType(): void
    {
        self::assertSame('bitbag_section', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new SectionStub(42, 'blog'));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $section = new SectionStub(42, 'blog');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_bitbag_section', $section);

        self::assertSame(42, $this->targetType->provideValue($request));
    }

    public function testProvideValueWithNoSection(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }

    public function testGetValueObject(): void
    {
        $section = new SectionStub(42, 'blog');

        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($section);

        self::assertSame($section, $this->targetType->getValueObject(42));
    }

    public function testGetValueObjectWithNoSection(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->targetType->getValueObject(42));
    }
}
