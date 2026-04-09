<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Cms\Layout\Resolver\TargetType\Collection;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Collection as CollectionStub;
use Netgen\Layouts\Sylius\Cms\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(Collection::class)]
final class CollectionTest extends TestCase
{
    use ValidatorTestCaseTrait;

    private Stub&CollectionRepositoryInterface $repositoryStub;

    private Collection $targetType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(CollectionRepositoryInterface::class);

        $this->targetType = new Collection($this->repositoryStub);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_cms_collection', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(new CollectionStub(42, 'blog'));

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $collection = new CollectionStub(42, 'blog');

        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_cms_collection', $collection);

        self::assertSame(42, $this->targetType->provideValue($request));
    }

    public function testProvideValueWithNoCollection(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }

    public function testGetValueObject(): void
    {
        $collection = new CollectionStub(42, 'blog');

        $this->repositoryStub
            ->method('find')
            ->willReturn($collection);

        self::assertSame($collection, $this->targetType->getValueObject(42));
    }

    public function testGetValueObjectWithNoCollection(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->targetType->getValueObject(42));
    }
}
