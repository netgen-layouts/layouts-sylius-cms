<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Parameters\Form\Mapper;
use Netgen\Layouts\Parameters\ParameterDefinition;

final class CollectionMapper extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserIntegerType::class;
    }

    public function mapOptions(ParameterDefinition $parameterDefinition): array
    {
        return [
            'item_type' => 'sylius_cms_collection',
            'required' => $parameterDefinition->isRequired,
        ];
    }
}
