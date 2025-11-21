<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBitBagBundle\Templating\Twig\Runtime;

use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;

final class BitBagRuntime
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private SectionRepositoryInterface $sectionRepository,
    ) {}

    public function getPageName(int|string $pageId): ?string
    {
        return $this->pageRepository->find($pageId)?->getName();
    }

    public function getSectionName(int|string $sectionId): ?string
    {
        return $this->sectionRepository->find($sectionId)?->getName();
    }
}
