<?php

namespace App\Account\Application\Command;

use App\Account\Domain\Entities\Account;
use App\Account\Infrastructure\Doctrine\AccountRepository;

class CreateAccountCommandHandler
{

    public function __construct(private readonly AccountRepository $accountRepository)
    {}

    public function __invoke(CreateAccountCommand $command): void
    {
        $account = new Account($command->initialBalance);
        $this->accountRepository->save($account);
    }
}