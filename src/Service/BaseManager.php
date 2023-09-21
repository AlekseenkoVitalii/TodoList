<?php

namespace App\Service;

use App\Exception\TaskNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

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

    public function getRepo($entity): ObjectRepository
    {
        return $this->em->getRepository($entity);
    }

    /**
     * @throws TaskNotFoundException
     */
    public function findOrError($entity, $id)
    {
        $object =  $this->getRepo($entity)->findOneBy(['id' => $id]);

        if(!$object) {
            throw new TaskNotFoundException();
        }

        return $object;
    }
}