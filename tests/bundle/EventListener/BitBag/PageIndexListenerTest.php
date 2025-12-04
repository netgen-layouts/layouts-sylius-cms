<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBitBagBundle\Tests\EventListener\BitBag;

use Netgen\Bundle\LayoutsSyliusBitBagBundle\EventListener\BitBag\PageIndexListener;
use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(PageIndexListener::class)]
final class PageIndexListenerTest extends TestCase
{
    private PageIndexListener $listener;

    private Stub&SectionRepositoryInterface $sectionRepositoryStub;

    private RequestStack $requestStack;

    private Context $context;

    protected function setUp(): void
    {
        $this->sectionRepositoryStub = self::createStub(SectionRepositoryInterface::class);
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $localeContextStub = self::createStub(LocaleContextInterface::class);
        $localeContextStub
            ->method('getLocaleCode')
            ->willReturn('en');

        $this->listener = new PageIndexListener(
            $this->sectionRepositoryStub,
            $localeContextStub,
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
        $request->attributes->set('sectionCode', 'blog');

        $this->requestStack->push($request);

        $section = new Section(42, 'blog');

        $this->sectionRepositoryStub
            ->method('findOneByCode')
            ->with(self::identicalTo('blog'), self::identicalTo('en'))
            ->willReturn($section);

        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertSame($section, $request->attributes->get('nglayouts_sylius_bitbag_section'));

        self::assertTrue($this->context->has('bitbag_section_id'));
        self::assertSame(42, $this->context->get('bitbag_section_id'));
    }

    public function testOnPageIndexWithoutRequest(): void
    {
        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertFalse($this->context->has('bitbag_section_id'));
    }

    public function testOnPageIndexWithoutSectionCode(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_bitbag_section'));
        self::assertFalse($this->context->has('bitbag_section_id'));
    }

    public function testOnPageIndexWithNonExistingSection(): void
    {
        $request = Request::create('/');
        $request->attributes->set('sectionCode', 'unknown');

        $this->requestStack->push($request);

        $this->sectionRepositoryStub
            ->method('findOneByCode')
            ->with(self::identicalTo('unknown'), self::identicalTo('en'))
            ->willReturn(null);

        $event = new ResourceControllerEvent();
        $this->listener->onPageIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_bitbag_section'));
        self::assertFalse($this->context->has('bitbag_section_id'));
    }
}
