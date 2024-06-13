<?php

namespace App\Account\Domain\Entities;

use App\Account\Infrastructure\Doctrine\TransactionRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'boolean')]
    private bool $isRecurrent;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $recurrentDay;

    public function __construct(Account $account, float $amount, string $type, string $description, \DateTimeInterface $date, bool $isRecurrent = false, ?int $recurrentDay = null)
    {
        $this->id = Uuid::v4();
        $this->account = $account;
        $this->amount = $amount;
        $this->type = $type;
        $this->description = $description;
        $this->date = $date;
        $this->isRecurrent = $isRecurrent;
        $this->recurrentDay = $recurrentDay;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function isRecurrent(): bool
    {
        return $this->isRecurrent;
    }

    public function getRecurrentDay(): ?int
    {
        return $this->recurrentDay;
    }
}
