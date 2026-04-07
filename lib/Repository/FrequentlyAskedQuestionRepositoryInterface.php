<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Repository;

use Pagerfanta\PagerfantaInterface;
use Sylius\CmsPlugin\Repository\FrequentlyAskedQuestionRepositoryInterface as BaseFrequentlyAskedQuestionRepositoryInterface;

interface FrequentlyAskedQuestionRepositoryInterface extends BaseFrequentlyAskedQuestionRepositoryInterface
{
    /**
     * Creates a paginator which is used to list frequently asked questions.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\FrequentlyAskedQuestion>
     */
    public function createListPaginator(string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for frequently asked questions.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Sylius\CmsPlugin\Entity\FrequentlyAskedQuestion>
     */
    public function createSearchPaginator(string $searchText, string $localeCode): PagerfantaInterface;
}
