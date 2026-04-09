<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Validator;

use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Collection;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_int;

final class CollectionValidator extends ConstraintValidator
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Collection) {
            throw new UnexpectedTypeException($constraint, Collection::class);
        }

        if (!is_int($value)) {
            throw new UnexpectedTypeException($value, 'int');
        }

        $collection = $this->collectionRepository->find($value);
        if (!$collection instanceof CollectionInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%collectionId%', (string) $value)
                ->addViolation();
        }
    }
}
