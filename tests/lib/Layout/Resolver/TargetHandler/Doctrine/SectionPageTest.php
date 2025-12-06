<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;
use Netgen\Layouts\Persistence\Values\LayoutResolver\RuleGroup;
use Netgen\Layouts\Persistence\Values\Status;
use Netgen\Layouts\Sylius\BitBag\Layout\Resolver\TargetHandler\Doctrine\SectionPage;
use Netgen\Layouts\Tests\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerTestBase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SectionPage::class)]
final class SectionPageTest extends TargetHandlerTestBase
{
    public function testMatchRules(): void
    {
        $rules = $this->handler->matchRules(
            $this->handler->loadRuleGroup(RuleGroup::ROOT_UUID, Status::Published),
            $this->getTargetIdentifier(),
            [1, 2, 43, 13],
        );

        self::assertCount(2, $rules);
        self::assertSame(6, $rules[0]->id);
    }

    protected function getTargetIdentifier(): string
    {
        return 'bitbag_section_page';
    }

    protected function getTargetHandler(): TargetHandlerInterface
    {
        return new SectionPage();
    }

    protected function provideFixturesPath(): string
    {
        return __DIR__ . '/../../../../../_fixtures';
    }
}
