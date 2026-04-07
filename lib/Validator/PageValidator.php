<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Validator;

use Netgen\Layouts\Sylius\Cms\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\Constraint\Page;
use Sylius\CmsPlugin\Entity\PageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_int;

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

        if (!is_int($value)) {
            throw new UnexpectedTypeException($value, 'int');
        }

        $page = $this->pageRepository->find($value);
        if (!$page instanceof PageInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%pageId%', (string) $value)
                ->addViolation();
        }
    }
}
