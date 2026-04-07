<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\SectionRepositoryInterface as BaseSectionRepositoryInterface;

interface SectionRepositoryInterface extends BaseSectionRepositoryInterface
{
    /**
     * Creates a paginator which is used to list sections.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Section>
     */
    public function createListPaginator(string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for sections.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\Section>
     */
    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface;
}
