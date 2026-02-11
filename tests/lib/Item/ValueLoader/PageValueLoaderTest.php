<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\BitBag\Item\ValueLoader\PageValueLoader;
use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(PageValueLoader::class)]
final class PageValueLoaderTest extends TestCase
{
    private Stub&PageRepositoryInterface $pageRepositoryStub;

    private PageValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->pageRepositoryStub = self::createStub(PageRepositoryInterface::class);
        $this->valueLoader = new PageValueLoader($this->pageRepositoryStub);
    }

    public function testLoad(): void
    {
        $page = new Page(42, 'about-us', 'About us');

        $this->pageRepositoryStub
            ->method('find')
            ->willReturn($page);

        self::assertSame($page, $this->valueLoader->load(42));
    }

    public function testLoadWithNoPage(): void
    {
        $this->pageRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->pageRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $page = new Page(42, 'about-us', 'About us');

        $this->pageRepositoryStub
            ->method('find')
            ->willReturn($page);

        self::assertSame($page, $this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithNoPage(): void
    {
        $this->pageRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->pageRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }
}
