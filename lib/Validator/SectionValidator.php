<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Validator;

use Netgen\Layouts\Sylius\Cms\Repository\SectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Section;
use Sylius\CmsPlugin\Entity\SectionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_int;

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

        if (!is_int($value)) {
            throw new UnexpectedTypeException($value, 'int');
        }

        $section = $this->sectionRepository->find($value);
        if (!$section instanceof SectionInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%sectionId%', (string) $value)
                ->addViolation();
        }
    }
}
