<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository)
    {

    }
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\UniqueUser $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        $existingUser = $this->userRepository->findOneBy([
            'email' => $value
        ]);

        if(!$existingUser)
            return;
        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
