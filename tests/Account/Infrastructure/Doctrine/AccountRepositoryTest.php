<?php

namespace App\Tests\Account\Infrastructure\Doctrine;

use App\Account\Domain\Entities\Account;
use App\Account\Infrastructure\Doctrine\AccountRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccountRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;
    private AccountRepository $accountRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->accountRepository = $this->entityManager->getRepository(Account::class);

        // Create schema
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema([$this->entityManager->getClassMetadata(Account::class)]);
    }

    public function testSaveAndFindAccount(): void
    {
        $account = new Account(1000.0);
        $this->accountRepository->save($account);

        $foundAccount = $this->accountRepository->findById($account->getId());
        $this->assertNotNull($foundAccount);
        $this->assertSame($account->getBalance(), $foundAccount->getBalance());
    }

    protected function tearDown(): void
    {
        // Drop schema
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema([$this->entityManager->getClassMetadata(Account::class)]);

        parent::tearDown();

        $this->entityManager->close();
    }
}
