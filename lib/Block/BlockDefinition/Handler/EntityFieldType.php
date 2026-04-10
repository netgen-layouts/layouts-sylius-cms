<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Block\BlockDefinition\Handler;

enum EntityFieldType: string
{
    case String = 'string';
    case Number = 'number';
    case Media = 'media';
    case DateTime = 'datetime';
    case Boolean = 'boolean';
    case Other = 'other';
    case Content = 'content';
    case ContentElements = 'content_elements';
}
