<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Item\Stubs;

use BitBag\SyliusCmsPlugin\Entity\Page as BasePage;

final class Page extends BasePage
{
    public function __construct(
        int $id,
        string $code,
        string $name,
        ?string $slug = null,
        bool $enabled = true,
    ) {
        parent::__construct();

        $this->id = $id;
        $this->setCode($code);
        $this->setEnabled($enabled);

        $this->setCurrentLocale('en');
        $this->setFallbackLocale('en');

        $this->setName($name);
        $this->setSlug($slug);
    }
}
