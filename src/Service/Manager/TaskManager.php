<?php

namespace App\Service\Manager;

use App\Entity\Task;
use App\Service\BaseManager;
use Doctrine\Persistence\ObjectRepository;

class TaskManager extends BaseManager
{
    public function getTaskRepo(): ObjectRepository
    {
        return $this->em->getRepository(Task::class);
    }

    public function getTaskAll(): array
    {
        return $this->getTaskRepo()->findBy(['parent' => null]);
    }
}
