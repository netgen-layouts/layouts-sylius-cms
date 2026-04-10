<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusCmsBundle\Tests\EventListener;

use Netgen\Bundle\LayoutsSyliusCmsBundle\EventListener\PageIndexListener;
use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

#[CoversClass(PageIndexListener::class)]
final class PageIndexListenerTest extends TestCase
{
    private PageIndexListener $listener;

    private Stub&CollectionRepositoryInterface $collectionRepositoryStub;

    private Context $context;

    protected function setUp(): void
    {
        $this->collectionRepositoryStub = self::createStub(CollectionRepositoryInterface::class);
        $this->context = new Context();

        $this->listener = new PageIndexListener(
            $this->collectionRepositoryStub,
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
        $request = Request::create('/collections/blog/pages');
        $request->attributes->set('_route', 'sylius_cms_shop_collections_page_index');
        $request->attributes->set('code', 'blog');

        $collection = new Collection(42, 'blog');

        $this->collectionRepositoryStub
            ->method('findOneByCode')
            ->willReturn($collection);

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertSame($collection, $request->attributes->get('nglayouts_sylius_cms_collection'));
        self::assertSame($collection, $request->attributes->get('nglayouts_sylius_resource'));
        self::assertTrue($this->context->has('sylius_cms_collection_id'));
        self::assertSame(42, $this->context->get('sylius_cms_collection_id'));
    }

    public function testOnKernelRequestWithWrongRoute(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'some_other_route');

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_collection'));
        self::assertFalse($this->context->has('sylius_cms_collection_id'));
    }

    public function testOnKernelRequestWithoutCode(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'sylius_cms_shop_collections_page_index');

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_collection'));
        self::assertFalse($this->context->has('sylius_cms_collection_id'));
    }

    public function testOnKernelRequestWithNonExistingCollection(): void
    {
        $request = Request::create('/collections/unknown/pages');
        $request->attributes->set('_route', 'sylius_cms_shop_collections_page_index');
        $request->attributes->set('code', 'unknown');

        $this->collectionRepositoryStub
            ->method('findOneByCode')
            ->willReturn(null);

        $event = $this->createRequestEvent($request);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_collection'));
        self::assertFalse($this->context->has('sylius_cms_collection_id'));
    }

    public function testOnKernelRequestWithSubRequest(): void
    {
        $request = Request::create('/collections/blog/pages');
        $request->attributes->set('_route', 'sylius_cms_shop_collections_page_index');
        $request->attributes->set('code', 'blog');

        $event = $this->createRequestEvent($request, HttpKernelInterface::SUB_REQUEST);
        $this->listener->onKernelRequest($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_collection'));
        self::assertFalse($this->context->has('sylius_cms_collection_id'));
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
