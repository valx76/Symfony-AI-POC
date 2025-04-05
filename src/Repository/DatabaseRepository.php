<?php

namespace App\Repository;

use App\Entity\Database;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Database>
 */
class DatabaseRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Database::class);
    }

    public function save(Database $database): void
    {
        $this->entityManager->persist($database);
        $this->entityManager->flush();
    }
}
