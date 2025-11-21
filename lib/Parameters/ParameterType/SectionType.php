<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint as BitBagConstraints;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a section in BitBag.
 */
final class SectionType extends ParameterType
{
    public static function getIdentifier(): string
    {
        return 'bitbag_section';
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new BitBagConstraints\Section(),
        ];
    }
}
