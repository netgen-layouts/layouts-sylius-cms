<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetType;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint as SyliusBitBagConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Section extends TargetType
{
    public static function getType(): string
    {
        return 'bitbag_section';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new SyliusBitBagConstraints\Section(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $section = $request->attributes->get('nglayouts_sylius_bitbag_section');

        return $section instanceof SectionInterface ? $section->getId() : null;
    }
}
