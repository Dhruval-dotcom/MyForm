<?php

namespace App\Form\Model;
use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    #[Assert\NotBlank(
        message: 'Choose a password'
    )]
    #[Assert\Email]
    #[UniqueUser]
    public $email;

    #[Assert\Length(
        min: 5,
        minMessage: 'Passwored with min value 5'
    )]
    public $plainPassword;

    #[Assert\IsTrue(
        message: 'You have to agree our terms and conditions'
    )]
    public $agreeTerms; 
}
