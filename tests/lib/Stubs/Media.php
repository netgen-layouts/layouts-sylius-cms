<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Stubs;

use Sylius\CmsPlugin\Entity\Media as BaseMedia;

final class Media extends BaseMedia
{
    public function __construct(int $id, string $code)
    {
        parent::__construct();

        $this->id = $id;
        $this->setCode($code);

        $this->setCurrentLocale('en');
        $this->setFallbackLocale('en');
    }
}
