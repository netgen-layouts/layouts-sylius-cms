<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Layout\Resolver\TargetType;

use Doctrine\Common\Collections\Collection;
use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Cms\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint as SyliusCmsConstraints;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Entity\SectionInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function array_map;

final class SectionPage extends TargetType implements ValueObjectProviderInterface
{
    public function __construct(
        private SectionRepositoryInterface $sectionRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_cms_section_page';
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

    /**
     * @return int[]|null
     */
    public function provideValue(Request $request): ?array
    {
        $page = $request->attributes->get('nglayouts_sylius_cms_page');
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

    public function getValueObject(int|string $value): ?ResourceInterface
    {
        return $this->sectionRepository->find((int) $value);
    }
}
