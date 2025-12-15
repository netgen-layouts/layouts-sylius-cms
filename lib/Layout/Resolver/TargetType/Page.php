<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetType;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint as SyliusBitBagConstraints;
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
        return 'bitbag_page';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'int'),
            new Constraints\Positive(),
            new SyliusBitBagConstraints\Page(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $page = $request->attributes->get('nglayouts_sylius_bitbag_page');

        return $page instanceof PageInterface ? $page->getId() : null;
    }

    public function getValueObject(mixed $value): ?ResourceInterface
    {
        return $this->pageRepository->find((int) $value);
    }
}
