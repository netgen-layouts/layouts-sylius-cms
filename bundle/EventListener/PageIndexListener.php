<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusCmsBundle\EventListener;

use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PageIndexListener implements EventSubscriberInterface
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
        private Context $context,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    /**
     * Sets the currently displayed collection to the request,
     * to be able to match with layout resolver.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if ($request->attributes->get('_route') !== 'sylius_cms_shop_collections_page_index') {
            return;
        }

        $collection = $this->collectionRepository->findOneByCode(
            $request->attributes->getString('code'),
        );

        if (!$collection instanceof CollectionInterface) {
            return;
        }

        $request->attributes->set('nglayouts_sylius_cms_collection', $collection);
        $request->attributes->set('nglayouts_sylius_resource', $collection);
        $this->context->set('nglayouts_sylius_cms_collection_id', (int) $collection->getId());
    }
}
