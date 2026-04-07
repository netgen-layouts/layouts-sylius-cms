<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Browser\ColumnProvider\FrequentlyAskedQuestion;

use Netgen\ContentBrowser\Item\ColumnProvider\ColumnValueProviderInterface;
use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\Cms\Browser\Item\FrequentlyAskedQuestion\FrequentlyAskedQuestionInterface;

final class Code implements ColumnValueProviderInterface
{
    public function getValue(ItemInterface $item): ?string
    {
        if (!$item instanceof FrequentlyAskedQuestionInterface) {
            return null;
        }

        return $item->frequentlyAskedQuestion->getCode();
    }
}
