<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Section::class)]
final class SectionTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new Section();
        self::assertSame('nglayouts_sylius_cms_section', $constraint->validatedBy());
    }
}
