<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\Validator;

use Netgen\Layouts\Sylius\Cms\Repository\PageRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Repository\CollectionRepositoryInterface;
use Netgen\Layouts\Sylius\Cms\Validator\PageValidator;
use Netgen\Layouts\Sylius\Cms\Validator\CollectionValidator;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class ValidatorFactory implements ConstraintValidatorFactoryInterface
{
    private ConstraintValidatorFactory $baseValidatorFactory;

    /**
     * @param \Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Sylius\Resource\Model\ResourceInterface> $repository
     */
    public function __construct(
        private RepositoryInterface $repository,
    ) {
        $this->baseValidatorFactory = new ConstraintValidatorFactory();
    }

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $name = $constraint->validatedBy();

        if ($name === 'nglayouts_sylius_cms_page' && $this->repository instanceof PageRepositoryInterface) {
            return new PageValidator($this->repository);
        }

        if ($name === 'nglayouts_sylius_cms_collection' && $this->repository instanceof CollectionRepositoryInterface) {
            return new CollectionValidator($this->repository);
        }

        return $this->baseValidatorFactory->getInstance($constraint);
    }
}
