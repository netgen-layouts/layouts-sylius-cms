<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\BitBag\Item\ValueLoader\MediaValueLoader;
use Netgen\Layouts\Sylius\BitBag\Repository\MediaRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs\Media;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(MediaValueLoader::class)]
final class MediaValueLoaderTest extends TestCase
{
    private Stub&MediaRepositoryInterface $mediaRepositoryStub;

    private MediaValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->mediaRepositoryStub = self::createStub(MediaRepositoryInterface::class);
        $this->valueLoader = new MediaValueLoader($this->mediaRepositoryStub);
    }

    public function testLoad(): void
    {
        $media = new Media(42, 'logo-image', 'Logo');

        $this->mediaRepositoryStub
            ->method('find')
            ->willReturn($media);

        self::assertSame($media, $this->valueLoader->load(42));
    }

    public function testLoadWithNoMedia(): void
    {
        $this->mediaRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->mediaRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $media = new Media(42, 'logo-image', 'Logo');

        $this->mediaRepositoryStub
            ->method('find')
            ->willReturn($media);

        self::assertSame($media, $this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithNoMedia(): void
    {
        $this->mediaRepositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->mediaRepositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId(42));
    }
}
