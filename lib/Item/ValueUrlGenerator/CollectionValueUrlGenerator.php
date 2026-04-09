<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueUrlGenerator;

use Netgen\Layouts\Item\ValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueUrlGeneratorInterface<\Sylius\CmsPlugin\Entity\CollectionInterface>
 */
final class CollectionValueUrlGenerator implements ValueUrlGeneratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function generateDefaultUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'bitbag_sylius_cms_plugin_shop_page_index_by_section_code',
            [
                'sectionCode' => $object->getCode(),
            ],
        );
    }

    public function generateAdminUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'bitbag_sylius_cms_plugin_admin_section_update',
            [
                'id' => $object->getId(),
            ],
        );
    }
}
