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

    public function findTaskByParameters(array $parameters, array $orderBy)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t.id, t.status, t.priority, t.title, t.description, t.createdAt, t.completedAt')
            ->where('t.executor = :executor')
            ->setParameter('executor', $parameters['executor'])
        ;
        if (array_key_exists('title', $parameters)) {
            $query
                ->andWhere('t.title LIKE :title')
                ->setParameter('title', '%' . $parameters['title'] . '%')
            ;
        }
        if (array_key_exists('status', $parameters)) {
            $query
                ->andWhere('t.status = :status')
                ->setParameter('status', $parameters['status'])
            ;
        }

        if (array_key_exists('priorityStart', $parameters)) {
            $query
                ->andWhere('t.priority >= :priorityStart')
                ->setParameter('priorityStart', $parameters['priorityStart'])
            ;
        }
        if (array_key_exists('priorityFinish', $parameters)) {
            $query
                ->andWhere('t.priority <= :priorityFinish')
                ->setParameter('priorityFinish', $parameters['priorityFinish'])
            ;
        }

        if ($orderBy) {
            array_key_exists('orderPriority', $orderBy) ? $query->addOrderBy('t.priority', $orderBy['orderPriority']) : null;
            array_key_exists('orderCreatedAt', $orderBy) ? $query->addOrderBy('t.createdAt', $orderBy['orderCreatedAt']) : null;
            array_key_exists('orderCompletedAt', $orderBy) ? $query->addOrderBy('t.completedAt', $orderBy['orderCompletedAt']) : null;
        }

        return  $query
            ->getQuery()
            ->getResult();
    }
}
