<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Item\Stubs;

use Sylius\CmsPlugin\Entity\Collection as BaseCollection;

final class Collection extends BaseCollection
{
    public function __construct(
        int $id,
        string $code,
        string $name,
    ) {
        parent::__construct();

        $this->id = $id;
        $this->setCode($code);
        $this->setName($name);
    }
}
