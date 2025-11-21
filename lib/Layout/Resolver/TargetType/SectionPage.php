<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetType;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use Doctrine\Common\Collections\Collection;
use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint as SyliusBitBagConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function array_map;

final class SectionPage extends TargetType
{
    public static function getType(): string
    {
        return 'bitbag_section_page';
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

    /**
     * @return int[]|null
     */
    public function provideValue(Request $request): ?array
    {
        $page = $request->attributes->get('nglayouts_sylius_bitbag_page');

        if (!$page instanceof PageInterface) {
            return null;
        }

        $sections = $page->getSections();
        if (!$sections instanceof Collection) {
            return [];
        }

        return array_map(
            static fn (SectionInterface $section): int => $section->getId(),
            $sections->getValues(),
        );
    }
}
