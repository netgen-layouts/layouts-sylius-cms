<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs;

use Sylius\CmsPlugin\Entity\Media as BaseMedia;

final class Media extends BaseMedia
{
    public function __construct(
        int $id,
        string $code,
        ?string $name = null,
        string $type = 'file',
        string $mimeType = '',
        bool $enabled = true,
    ) {
        parent::__construct();

        $this->id = $id;
        $this->setCode($code);
        $this->setEnabled($enabled);

        $this->setCurrentLocale('en');
        $this->setFallbackLocale('en');

        $this->setName($name);
        $this->setType($type);
        $this->setMimeType($mimeType);
    }
}
