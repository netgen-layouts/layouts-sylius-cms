<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetType;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint as SyliusBitBagConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Page extends TargetType
{
    public static function getType(): string
    {
        return 'bitbag_page';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new SyliusBitBagConstraints\Page(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $page = $request->attributes->get('nglayouts_sylius_bitbag_page');

        return $page instanceof PageInterface ? $page->getId() : null;
    }
}
