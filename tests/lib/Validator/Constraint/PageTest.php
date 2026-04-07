<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Page::class)]
final class PageTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new Page();
        self::assertSame('nglayouts_sylius_cms_page', $constraint->validatedBy());
    }
}
