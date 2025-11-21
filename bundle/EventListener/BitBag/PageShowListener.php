<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBitBagBundle\EventListener\BitBag;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use Netgen\Layouts\Context\Context;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PageShowListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private Context $context,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return ['bitbag_sylius_cms_plugin.page.show' => 'onPageShow'];
    }

    /**
     * Sets the currently displayed page to the request,
     * to be able to match with layout resolver.
     */
    public function onPageShow(ResourceControllerEvent $event): void
    {
        $page = $event->getSubject();
        if (!$page instanceof PageInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest instanceof Request) {
            $currentRequest->attributes->set('nglayouts_sylius_bitbag_page', $page);
            // We set context here instead in a ContextProvider, since bitbag_sylius_cms_plugin.page.show
            // event happens too late, after onKernelRequest event has already been executed
            $this->context->set('bitbag_page_id', (int) $page->getId());
        }
    }
}
