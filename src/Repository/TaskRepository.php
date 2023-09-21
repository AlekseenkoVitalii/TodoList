<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findTaskByParameters(array $parameters)
    {
        return $this->createQueryBuilder('t')
            ->where('t. executor = :executor')
            ->setParameter('executor', $parameters['executor'])
            ->andWhere('t.status = :status')
            ->setParameter('status', $parameters['status'])
//            ->andWhere('c.dateFrom <= :now')
//            ->andWhere('c.dateTo >= :now')
//            ->setParameter('now', (new \DateTime())->setTime(0, 0, 0))
            ->getQuery()
            ->getResult();
    }
}
