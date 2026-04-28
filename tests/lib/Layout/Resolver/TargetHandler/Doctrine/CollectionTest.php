<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\Layouts\Persistence\Values\LayoutResolver\RuleGroup;
use Netgen\Layouts\Persistence\Values\Status;
use Netgen\Layouts\Sylius\Cms\Layout\Resolver\TargetHandler\Doctrine\Collection;
use Netgen\Layouts\Tests\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerTestBase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Collection::class)]
final class CollectionTest extends TargetHandlerTestBase
{
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules(
            $this->handler->loadRuleGroup(RuleGroup::ROOT_UUID, Status::Published),
            $this->getTargetIdentifier(),
            2,
        );

        self::assertCount(1, $rules);
        self::assertSame(4, $rules[0]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'sylius_cms_collection';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new Collection();
    }

    protected function provideFixturesPath(): string
    {
        return __DIR__ . '/../../../../../_fixtures';
    }
}
