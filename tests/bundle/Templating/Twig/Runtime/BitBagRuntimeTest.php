<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBitBagBundle\Tests\Templating\Twig\Runtime;

use Netgen\Bundle\LayoutsSyliusBitBagBundle\Templating\Twig\Runtime\BitBagRuntime;
use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Page;
use Netgen\Layouts\Sylius\BitBag\Tests\Stubs\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(BitBagRuntime::class)]
final class BitBagRuntimeTest extends TestCase
{
    private MockObject&PageRepositoryInterface $pageRepositoryMock;

    private MockObject&SectionRepositoryInterface $sectionRepositoryMock;

    private BitBagRuntime $runtime;

    protected function setUp(): void
    {
        $this->pageRepositoryMock = $this->createMock(PageRepositoryInterface::class);
        $this->sectionRepositoryMock = $this->createMock(SectionRepositoryInterface::class);

        $this->runtime = new BitBagRuntime(
            $this->pageRepositoryMock,
            $this->sectionRepositoryMock,
        );
    }

    public function testGetPageName(): void
    {
        $page = new Page(15, 'about-us');
        $page->setCurrentLocale('en');
        $page->setName('About us');

        $this->pageRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(15))
            ->willReturn($page);

        self::assertSame('About us', $this->runtime->getPageName(15));
    }

    public function testGetSectionName(): void
    {
        $section = new Section(5, 'articles');
        $section->setCurrentLocale('en');
        $section->setName('Articles');

        $this->sectionRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::identicalTo(5))
            ->willReturn($section);

        self::assertSame('Articles', $this->runtime->getSectionName(5));
    }
}
