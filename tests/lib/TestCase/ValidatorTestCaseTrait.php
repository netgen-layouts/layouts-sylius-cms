<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Cms\Tests\TestCase;

use Netgen\Layouts\Sylius\Cms\Tests\Validator\ValidatorFactory;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorTestCaseTrait
{
    /**
     * @template T of \Sylius\Resource\Model\ResourceInterface
     *
     * @param \Sylius\Resource\Doctrine\Persistence\RepositoryInterface<T>|null $repository
     */
    private function createValidator(
        ?RepositoryInterface $repository = null,
    ): ValidatorInterface {
        $repository ??= self::createStub(RepositoryInterface::class);

        return Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(
                new ValidatorFactory($repository),
            )
            ->getValidator();
    }
}
