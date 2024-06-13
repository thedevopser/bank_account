<?php

namespace App\Account\Infrastructure\Doctrine;

use App\Account\Domain\Entities\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Transaction::class);
        $this->entityManager = $entityManager;
    }

    public function save(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function findById(Uuid $id): ?Transaction
    {
        return $this->find($id);
    }

    /**
     * @param Uuid $accountId
     * @return Transaction[]
     */
    public function findByAccountId(Uuid $accountId): array
    {
        /** @var Transaction[] $result */
        $result = $this->createQueryBuilder('t')
            ->andWhere('t.account = :accountId')
            ->setParameter('accountId', $accountId)
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function remove(Transaction $transaction): void
    {
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();
    }
}
