<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Collection::class)]
final class CollectionTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new Collection();
        self::assertSame('nglayouts_sylius_cms_collection', $constraint->validatedBy());
    }
}
