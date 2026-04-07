<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\Cms\Item\ValueLoader\BlockValueLoader;
use Netgen\Layouts\Sylius\Cms\Repository\BlockRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs\Block;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(BlockValueLoader::class)]
final class BlockValueLoaderTest extends TestCase
{
    private Stub&BlockRepositoryInterface $blockRepositoryStub;

    private BlockValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->blockRepositoryStub = self::createStub(BlockRepositoryInterface::class);
        $this->valueLoader = new BlockValueLoader($this->blockRepositoryStub);
    }

    public function testLoad(): void
    {
        $block = new Block(42, 'header', 'Header');

        $this->blockRepositoryStub
            ->method('find')
            ->willReturn($block);

        self::assertSame($block, $this->valueLoader->load(42));
    }

    public function testLoadWithNoBlock(): void
    {
        $this->blockRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->blockRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $block = new Block(42, 'header', 'Header');

        $this->blockRepositoryStub
            ->method('find')
            ->willReturn($block);

        self::assertSame($block, $this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithNoBlock(): void
    {
        $this->blockRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->blockRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }
}
