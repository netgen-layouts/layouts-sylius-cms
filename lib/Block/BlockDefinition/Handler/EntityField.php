<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Block\BlockDefinition\Handler;

use DateTimeInterface;
use Sylius\CmsPlugin\Entity\ContentableInterface;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\Resource\Model\ResourceInterface;

use function is_bool;
use function is_numeric;
use function is_string;
use function method_exists;
use function ucfirst;

final class EntityField
{
    private const string CONTENT_FIELD_IDENTIFIER = 'content';

    public private(set) EntityFieldType $type;

    private function __construct(
        public private(set) mixed $value,
    ) {
        $this->type = $this->resolveType($this->value);
    }

    public static function fromEntity(ResourceInterface $resource, string $fieldIdentifier): self
    {
        if ($resource instanceof ContentableInterface && $fieldIdentifier === self::CONTENT_FIELD_IDENTIFIER) {
            return new self($resource);
        }

        $methodName = 'get' . ucfirst($fieldIdentifier);

        if (method_exists($resource, $methodName)) {
            $value = ($resource->{$methodName}(...))();

            return new self($value);
        }

        $methodName = 'is' . ucfirst($fieldIdentifier);

        if (method_exists($resource, $methodName)) {
            $value = ($resource->{$methodName}(...))();

            return new self($value);
        }

        return new self(null);
    }

    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    private function resolveType(mixed $value): EntityFieldType
    {
        return match (true) {
            $value instanceof DateTimeInterface => EntityFieldType::DateTime,
            is_string($value) => EntityFieldType::String,
            is_numeric($value) => EntityFieldType::Number,
            is_bool($value) => EntityFieldType::Boolean,
            $value instanceof MediaInterface => EntityFieldType::Media,
            $value instanceof ContentableInterface => EntityFieldType::Content,
            default => EntityFieldType::Other,
        };
    }
}
