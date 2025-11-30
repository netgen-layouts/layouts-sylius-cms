<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\BitBag\Validator;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use Netgen\Layouts\Sylius\BitBag\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\BitBag\Validator\Constraint\Page;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_scalar;

final class PageValidator extends ConstraintValidator
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Page) {
            throw new UnexpectedTypeException($constraint, Page::class);
        }

        if (!is_scalar($value)) {
            throw new UnexpectedTypeException($value, 'scalar');
        }

        $page = $this->pageRepository->find($value);
        if (!$page instanceof PageInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%pageId%', (string) $value)
                ->addViolation();
        }
    }
}
