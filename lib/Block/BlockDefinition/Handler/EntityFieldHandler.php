<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Block\BlockDefinition\Handler;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\BlockDefinitionHandler;
use Netgen\Layouts\Block\DynamicParameters;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class EntityFieldHandler extends BlockDefinitionHandler
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add(
            'field_identifier',
            ParameterType\IdentifierType::class,
        );

        $builder->add(
            'html_element',
            ParameterType\ChoiceType::class,
            [
                'options' => [
                    'Div' => 'div',
                    'Span' => 'span',
                    'Paragraph' => 'p',
                    'Heading 1' => 'h1',
                    'Heading 2' => 'h2',
                    'Heading 3' => 'h3',
                ],
                'multiple' => false,
            ],
        );

        $builder->add(
            'datetime_format',
            ParameterType\TextLineType::class,
            [
                'required' => true,
                'default_value' => 'Y-m-d',
            ],
        );
    }

    public function getDynamicParameters(DynamicParameters $params, Block $block): void
    {
        $fieldIdentifier = $block->getParameter('field_identifier')->value;
        $entity = $this->getCurrentBitBagEntity();

        $params['field'] = null;
        if ($entity instanceof ResourceInterface) {
            $params['field'] = BitBagEntityField::fromBitBagEntity($entity, $fieldIdentifier);
        }
    }

    public function isContextual(Block $block): true
    {
        return true;
    }

    private function getCurrentBitBagEntity(): ?ResourceInterface
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return null;
        }

        $page = $currentRequest->attributes->get('nglayouts_sylius_bitbag_page');
        if ($page instanceof PageInterface) {
            return $page;
        }

        $section = $currentRequest->attributes->get('nglayouts_sylius_bitbag_section');
        if ($section instanceof SectionInterface) {
            return $section;
        }

        return null;
    }
}
