<?php

namespace App\Account\Application\Query;

use Symfony\Component\Uid\Uuid;

class GetAccountBalanceQuery
{
    private Uuid $accountId;

    public function __construct(Uuid $accountId)
    {
        $this->accountId = $accountId;
    }

    public function getAccountId(): Uuid
    {
        return $this->accountId;
    }
}