<?php

namespace App\Account\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountCommand
{
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    public float $initialBalance;
}