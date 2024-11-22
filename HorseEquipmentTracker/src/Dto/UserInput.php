<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/',
        message: 'Password must contain at least one letter, one number, and one special character.'
    )]
    public ?string $plainPassword = null;

    #[Assert\NotBlank]
    #[Assert\EqualTo(
        propertyPath: 'plainPassword',
        message: 'Password confirmation must match password.'
    )]
    public ?string $confirmPassword = null;
}