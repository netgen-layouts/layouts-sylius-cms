<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Layout\Resolver\TargetType;

use BitBag\SyliusCmsPlugin\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetType\Page;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Page as PageStub;
use Netgen\Layouts\Sylius\BitBag\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

#[CoversClass(Page::class)]
final class PageTest extends TestCase
{
    private MockObject&PageRepositoryInterface $repositoryMock;

    private Page $targetType;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(PageRepositoryInterface::class);

        $this->targetType = new Page();
    }

    public function testGetType(): void
    {
        self::assertSame('bitbag_page', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new PageStub(42, 'contact-us'));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $page = new PageStub(42, 'contact-us');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_bitbag_page', $page);

        self::assertSame(42, $this->targetType->provideValue($request));
    }

    public function testProvideValueWithNoPage(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }
}
