<?php

namespace App\Tests\Account\Infrastructure\Doctrine;

use App\Account\Domain\Entities\Account;
use App\Account\Domain\Entities\Transaction;
use App\Account\Infrastructure\Doctrine\AccountRepository;
use App\Account\Infrastructure\Doctrine\TransactionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TransactionRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;
    private AccountRepository $accountRepository;
    private TransactionRepository $transactionRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->accountRepository = $this->entityManager->getRepository(Account::class);
        $this->transactionRepository = $this->entityManager->getRepository(Transaction::class);

        // Create schema
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(Account::class),
            $this->entityManager->getClassMetadata(Transaction::class)
        ]);
    }

    public function testSaveAndFindTransaction(): void
    {
        $account = new Account(1000.0);
        $this->accountRepository->save($account);

        $transaction = new Transaction($account, 200.0, 'CREDIT', 'Test transaction', new \DateTimeImmutable(), false, null);
        $this->transactionRepository->save($transaction);

        $foundTransaction = $this->transactionRepository->findById($transaction->getId());
        $this->assertNotNull($foundTransaction);
        $this->assertSame($transaction->getAmount(), $foundTransaction->getAmount());
    }

    protected function tearDown(): void
    {
        // Drop schema
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema([
            $this->entityManager->getClassMetadata(Account::class),
            $this->entityManager->getClassMetadata(Transaction::class)
        ]);

        parent::tearDown();

        $this->entityManager->close();
    }
}
