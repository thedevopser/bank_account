<?php

namespace App\Account\Application\Command;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTransactionCommand
{
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Choice(['CREDIT', 'DEBIT'])]
    public string $type;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Type('\DateTimeInterface')]
    public \DateTimeInterface $date;

    #[Assert\Type('bool')]
    public bool $isRecurrent = false;

    #[Assert\Type('int')]
    #[Assert\Range(min: 1, max: 31)]
    public ?int $recurrentDay = null;

    private Uuid $accountId;

    public static function fromAccount(Uuid $accountId): self
    {
        $dto = new self();
        $dto->accountId = $accountId;
        return $dto;
    }

    public function getAccountId(): Uuid
    {
        return $this->accountId;
    }


}