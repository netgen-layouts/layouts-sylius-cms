<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Item\ValueConverter;

use BitBag\SyliusCmsPlugin\Entity\FrequentlyAskedQuestionInterface;
use Netgen\Layouts\Item\ValueConverterInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\BitBag\SyliusCmsPlugin\Entity\FrequentlyAskedQuestionInterface>
 */
final class FrequentlyAskedQuestionValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof FrequentlyAskedQuestionInterface;
    }

    public function getValueType(object $object): string
    {
        return 'bitbag_frequently_asked_question';
    }

    public function getId(object $object): int
    {
        return $object->getId();
    }

    public function getRemoteId(object $object): int
    {
        return $object->getId();
    }

    public function getName(object $object): string
    {
        return (string) $object->getQuestion();
    }

    public function getIsVisible(object $object): bool
    {
        return $object->isEnabled();
    }

    public function getObject(object $object): FrequentlyAskedQuestionInterface
    {
        return $object;
    }
}
