<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusCmsBundle\Tests\EventListener;

use Netgen\Bundle\LayoutsSyliusCmsBundle\EventListener\PageShowListener;
use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Page;
use Netgen\Layouts\Sylius\Cms\Tests\Stubs\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(PageShowListener::class)]
final class PageShowListenerTest extends TestCase
{
    private PageShowListener $listener;

    private RequestStack $requestStack;

    private Context $context;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->listener = new PageShowListener($this->requestStack, $this->context);
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['bitbag_sylius_cms_plugin.page.show' => 'onPageShow'],
            $this->listener::getSubscribedEvents(),
        );
    }

    public function testOnPageShow(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $page = new Page(42, 'contact');
        $event = new ResourceControllerEvent($page);
        $this->listener->onPageShow($event);

        self::assertSame($page, $request->attributes->get('nglayouts_sylius_cms_page'));

        self::assertTrue($this->context->has('sylius_cms_page_id'));
        self::assertSame(42, $this->context->get('sylius_cms_page_id'));
    }

    public function testOnPageShowWithoutPage(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $section = new Section(5, 'blog');
        $event = new ResourceControllerEvent($section);
        $this->listener->onPageShow($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_cms_page'));
        self::assertFalse($this->context->has('sylius_cms_page_id'));
    }
}
