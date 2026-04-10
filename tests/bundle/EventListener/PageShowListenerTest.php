<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusCmsBundle\Tests\EventListener;

use Netgen\Bundle\LayoutsSyliusCmsBundle\EventListener\PageShowListener;
use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Cms\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\Channel;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

#[CoversClass(PageShowListener::class)]
final class PageShowListenerTest extends TestCase
{
    private PageShowListener $listener;

    private Stub&PageRepositoryInterface $pageRepositoryStub;

    private Context $context;

    protected function setUp(): void
    {
        $this->pageRepositoryStub = self::createStub(PageRepositoryInterface::class);
        $this->context = new Context();

        $localeContextStub = self::createStub(LocaleContextInterface::class);
        $localeContextStub
            ->method('getLocaleCode')
            ->willReturn('en');

        $channel = new Channel();
        $channel->setCode('default');

        $channelContextStub = self::createStub(ChannelContextInterface::class);
        $channelContextStub
            ->method('getChannel')
            ->willReturn($channel);

        $this->listener = new PageShowListener(
            $this->pageRepositoryStub,
            $localeContextStub,
            $channelContextStub,
            $this->context,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [KernelEvents::REQUEST => 'onKernelRequest'],
            $this->listener::getSubscribedEvents(),
        );
    }

    public function testOnKernelRequest(): void
    {
        $request = Request::create('/pages/contact');
        $request->attributes->set('_route', 'sylius_cms_shop_page_show');
        $request->attributes->set('slug', 'contact');

        $page = new Page(42, 'contact');

        $this->pageRepositoryStub
            ->method('findOneEnabledBySlugAndChannelCode')
            ->willReturn($page);

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertSame($page, $request->attributes->get('nglayouts_sylius_cms_page'));
        self::assertTrue($this->context->has('sylius_cms_page_id'));
        self::assertSame(42, $this->context->get('sylius_cms_page_id'));
    }

    public function testOnKernelRequestWithWrongRoute(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'some_other_route');

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_page'));
        self::assertFalse($this->context->has('sylius_cms_page_id'));
    }

    public function testOnKernelRequestWithoutSlug(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'sylius_cms_shop_page_show');

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_page'));
        self::assertFalse($this->context->has('sylius_cms_page_id'));
    }

    public function testOnKernelRequestWithNonExistingPage(): void
    {
        $request = Request::create('/pages/unknown');
        $request->attributes->set('_route', 'sylius_cms_shop_page_show');
        $request->attributes->set('slug', 'unknown');

        $this->pageRepositoryStub
            ->method('findOneEnabledBySlugAndChannelCode')
            ->willReturn(null);

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_page'));
        self::assertFalse($this->context->has('sylius_cms_page_id'));
    }

    public function testOnKernelRequestWithSubRequest(): void
    {
        $request = Request::create('/pages/contact');
        $request->attributes->set('_route', 'sylius_cms_shop_page_show');
        $request->attributes->set('slug', 'contact');

        $event = $this->createRequestEvent($request, HttpKernelInterface::SUB_REQUEST);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_page'));
        self::assertFalse($this->context->has('sylius_cms_page_id'));
    }

    private function createRequestEvent(Request $request, int $requestType = HttpKernelInterface::MAIN_REQUEST): RequestEvent
    {
        return new RequestEvent(
            self::createStub(HttpKernelInterface::class),
            $request,
            $requestType,
        );
    }
}
