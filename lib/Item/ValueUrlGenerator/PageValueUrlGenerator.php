<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Item\ValueUrlGenerator;

use Netgen\Layouts\Item\ValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueUrlGeneratorInterface<\Sylius\CmsPlugin\Entity\PageInterface>
 */
final class PageValueUrlGenerator implements ValueUrlGeneratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function generateDefaultUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'sylius_cms_shop_page_show',
            [
                'slug' => $object->getSlug(),
            ],
        );
    }

    public function generateAdminUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'sylius_cms_admin_page_update',
            [
                'id' => $object->getId(),
            ],
        );
    }
}
