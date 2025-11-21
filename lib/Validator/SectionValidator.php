<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Validator;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use BitBag\SyliusCmsPlugin\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint\Section;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_scalar;

final class SectionValidator extends ConstraintValidator
{
    public function __construct(
        private SectionRepositoryInterface $sectionRepository,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Section) {
            throw new UnexpectedTypeException($constraint, Section::class);
        }

        if (!is_scalar($value)) {
            throw new UnexpectedTypeException($value, 'scalar');
        }

        $section = $this->sectionRepository->find($value);
        if (!$section instanceof SectionInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%sectionId%', (string) $value)
                ->addViolation();
        }
    }
}
