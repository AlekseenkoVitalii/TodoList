<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class BaseManager
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save($entity, bool $andFlush = true): void
    {
        $this->em->persist($entity);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    public function update($entity): void
    {
        $this->em->flush($entity);
    }

    public function remove($entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function newEntity($entityName)
    {
        return new $entityName();
    }
}