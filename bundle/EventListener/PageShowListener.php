<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusCmsBundle\EventListener;

use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Cms\Repository\PageRepositoryInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PageShowListener implements EventSubscriberInterface
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private LocaleContextInterface $localeContext,
        private ChannelContextInterface $channelContext,
        private Context $context,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    /**
     * Sets the currently displayed page to the request,
     * to be able to match with layout resolver.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if ($request->attributes->get('_route') !== 'sylius_cms_shop_page_show') {
            return;
        }

        $channelCode = $this->channelContext->getChannel()->getCode();
        if ($channelCode === null) {
            return;
        }

        $page = $this->pageRepository->findOneEnabledBySlugAndChannelCode(
            $request->attributes->getString('slug'),
            $this->localeContext->getLocaleCode(),
            $channelCode,
        );

        if (!$page instanceof PageInterface) {
            return;
        }

        $request->attributes->set('nglayouts_sylius_cms_page', $page);
        $this->context->set('sylius_cms_page_id', (int) $page->getId());
    }
}
