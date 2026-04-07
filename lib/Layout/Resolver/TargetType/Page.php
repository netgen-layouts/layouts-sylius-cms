<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Cms\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint as SyliusCmsConstraints;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Page extends TargetType implements ValueObjectProviderInterface
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_cms_page';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'int'),
            new Constraints\Positive(),
            new SyliusCmsConstraints\Page(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $page = $request->attributes->get('nglayouts_sylius_cms_page');

        return $page instanceof PageInterface ? $page->getId() : null;
    }

    public function getValueObject(int|string $value): ?ResourceInterface
    {
        return $this->pageRepository->find((int) $value);
    }
}
