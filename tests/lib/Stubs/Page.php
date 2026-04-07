<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Stubs;

use Sylius\CmsPlugin\Entity\Page as BasePage;

final class Page extends BasePage
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
