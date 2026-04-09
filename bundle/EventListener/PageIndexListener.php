<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusCmsBundle\EventListener;

use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PageIndexListener implements EventSubscriberInterface
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
        private RequestStack $requestStack,
        private Context $context,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return ['bitbag_sylius_cms_plugin.page.index' => 'onPageIndex'];
    }

    /**
     * Sets the currently displayed collection to the request,
     * to be able to match with layout resolver.
     */
    public function onPageIndex(ResourceControllerEvent $event): void
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return;
        }

        // Only sane way to extract the reference to the collection
        if (!$currentRequest->attributes->has('collectionCode')) {
            return;
        }

        $collection = $this->collectionRepository->findOneByCode(
            $currentRequest->attributes->getString('collectionCode'),
        );

        if (!$collection instanceof CollectionInterface) {
            return;
        }

        $currentRequest->attributes->set('nglayouts_sylius_cms_collection', $collection);
        $currentRequest->attributes->set('nglayouts_sylius_resource', $collection);
        // We set context here instead in a ContextProvider, since bitbag_sylius_cms_plugin.page.index
        // event happens too late, after onKernelRequest event has already been executed
        $this->context->set('sylius_cms_collection_id', (int) $collection->getId());
    }
}
