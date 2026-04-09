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
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(PageIndexListener::class)]
final class PageIndexListenerTest extends TestCase
{
    private PageIndexListener $listener;

    private Stub&CollectionRepositoryInterface $collectionRepositoryStub;

    private RequestStack $requestStack;

    private Context $context;

    protected function setUp(): void
    {
        $this->collectionRepositoryStub = self::createStub(CollectionRepositoryInterface::class);
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->listener = new PageIndexListener(
            $this->collectionRepositoryStub,
            $this->requestStack,
            $this->context,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['bitbag_sylius_cms_plugin.page.index' => 'onPageIndex'],
            $this->listener::getSubscribedEvents(),
        );
    }

    public function testOnPageIndex(): void
    {
        $request = Request::create('/');
        $request->attributes->set('collectionCode', 'blog');

        $this->requestStack->push($request);

        $collection = new Collection(42, 'blog');

        $this->collectionRepositoryStub
            ->method('findOneByCode')
            ->willReturn($collection);

        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertSame($collection, $request->attributes->get('nglayouts_sylius_cms_collection'));

        self::assertTrue($this->context->has('sylius_cms_collection_id'));
        self::assertSame(42, $this->context->get('sylius_cms_collection_id'));
    }

    public function testOnPageIndexWithoutRequest(): void
    {
        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertFalse($this->context->has('sylius_cms_collection_id'));
    }

    public function testOnPageIndexWithoutCollectionCode(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_collection'));
        self::assertFalse($this->context->has('sylius_cms_collection_id'));
    }

    public function testOnPageIndexWithNonExistingCollection(): void
    {
        $request = Request::create('/');
        $request->attributes->set('collectionCode', 'unknown');

        $this->requestStack->push($request);

        $this->collectionRepositoryStub
            ->method('findOneByCode')
            ->willReturn(null);

        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_collection'));
        self::assertFalse($this->context->has('sylius_cms_collection_id'));
    }
}
