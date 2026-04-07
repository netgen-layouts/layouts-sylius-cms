<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Cms\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint as SyliusCmsConstraints;
use Sylius\CmsPlugin\Entity\SectionInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Section extends TargetType implements ValueObjectProviderInterface
{
    public function __construct(
        private SectionRepositoryInterface $sectionRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_cms_section';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'int'),
            new Constraints\Positive(),
            new SyliusCmsConstraints\Section(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $section = $request->attributes->get('nglayouts_sylius_cms_section');

        return $section instanceof SectionInterface ? $section->getId() : null;
    }

    public function getValueObject(int|string $value): ?ResourceInterface
    {
        return $this->sectionRepository->find((int) $value);
    }
}
