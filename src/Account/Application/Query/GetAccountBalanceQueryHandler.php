<?php

namespace App\Account\Application\Query;

use App\Account\Infrastructure\Doctrine\AccountRepository;
use App\Account\Infrastructure\Doctrine\TransactionRepository;

class GetAccountBalanceQueryHandler
{
    private AccountRepository $accountRepository;
    private TransactionRepository $transactionRepository;

    public function __construct(AccountRepository $accountRepository, TransactionRepository $transactionRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function __invoke(GetAccountBalanceQuery $query): float
    {
        $account = $this->accountRepository->findById($query->getAccountId());

        if (!$account) {
            throw new \Exception('Account not found');
        }

        $transactions = $this->transactionRepository->findPastByAccountId($query->getAccountId(), new \DateTime());
        $balance = $account->getBalance();

        foreach ($transactions as $transaction) {
            $balance += $transaction->getType() === 'CREDIT' ? $transaction->getAmount() : -$transaction->getAmount();
        }

        return $balance;
    }
}