<?php

namespace App\Account\Application\Command;

use App\Account\Domain\Entities\Transaction;
use App\Account\Infrastructure\Doctrine\AccountRepository;
use App\Account\Infrastructure\Doctrine\TransactionRepository;

class CreateTransactionCommandHandler
{

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly TransactionRepository $transactionRepository)
    {}

    public function __invoke(CreateTransactionCommand $command): void
    {
        $account = $this->accountRepository->findById($command->getAccountId());

        if ($account === null) {
            throw new \Exception('Account not found.');
        }

        $transaction = new Transaction(
            $account,
            $command->amount,
            $command->type,
            $command->description,
            $command->date,
            $command->isRecurrent,
            $command->recurrentDay
        );

        $this->transactionRepository->save($transaction);
    }
}