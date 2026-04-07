<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueUrlGenerator;

use Netgen\Layouts\Item\ValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueUrlGeneratorInterface<\Sylius\CmsPlugin\Entity\BlockInterface>
 */
final class BlockValueUrlGenerator implements ValueUrlGeneratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function generateDefaultUrl(object $object): null
    {
        return null;
    }

    public function generateAdminUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'bitbag_sylius_cms_plugin_admin_block_update',
            [
                'id' => $object->getId(),
            ],
        );
    }
}
