<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Cms\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint as SyliusCmsConstraints;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a section in Sylius CMS.
 */
final class SectionType extends ParameterType implements ValueObjectProviderInterface
{
    public function __construct(
        private SectionRepositoryInterface $sectionRepository,
    ) {}

    public static function getIdentifier(): string
    {
        return 'sylius_cms_section';
    }

    public function fromHash(ParameterDefinition $parameterDefinition, mixed $value): ?int
    {
        return $value !== null ? (int) $value : null;
    }

    public function getValueObject(mixed $value): ?ResourceInterface
    {
        return $this->sectionRepository->find((int) $value);
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\Type(type: 'int'),
            new Constraints\Positive(),
            new SyliusCmsConstraints\Section(),
        ];
    }
}
