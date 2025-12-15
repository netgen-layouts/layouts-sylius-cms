<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Parameters\Form\Mapper;
use Netgen\Layouts\Parameters\ParameterDefinition;

final class SectionMapper extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserIntegerType::class;
    }

    public function mapOptions(ParameterDefinition $parameterDefinition): array
    {
        return [
            'item_type' => 'bitbag_section',
            'required' => $parameterDefinition->isRequired,
        ];
    }
}
