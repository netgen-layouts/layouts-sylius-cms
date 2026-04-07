<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs;

use Sylius\CmsPlugin\Entity\Block as BaseBlock;

final class Block extends BaseBlock
{
    public function __construct(
        int $id,
        string $code,
        string $name,
        bool $enabled = true,
    ) {
        parent::__construct();

        $this->id = $id;
        $this->setCode($code);
        $this->setEnabled($enabled);

        $this->setCurrentLocale('en');
        $this->setFallbackLocale('en');

        $this->setName($name);
    }
}
