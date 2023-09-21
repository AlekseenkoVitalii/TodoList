<?php

namespace App\Service\Manager;

use App\Constants\TaskStatusConstant;
use App\Entity\Task;
use App\Exception\AccessDeniedException;
use App\Exception\TaskCompletionException;
use App\Exception\TaskDeleteCompletedException;
use App\Exception\TaskDeletionException;
use App\Exception\TaskNotFoundException;
use App\Service\BaseManager;
use Carbon\CarbonImmutable;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskManager extends BaseManager
{
    public function getTasksAllBy(UserInterface $user, $parameters, $orderBy): array
    {
        if (!$parameters) {
            $data = $this->getRepo(Task::class)->findBy(['parent' => null, 'executor' => $user], $orderBy);
        } else {
            $data = $this->getRepo(Task::class)->findTaskByParameters($parameters, $orderBy);
        }

        return ['data' => $data];
    }

    /**
     * @throws AccessDeniedException
     * @throws TaskNotFoundException
     */
    public function getTaskOne($id, $user): Task
    {
        $task = $this->findOrError(Task::class, $id);

        if($task->getExecutor() !== $user) {
            throw new AccessDeniedException();
        }

        return $task;
    }

    /**
     * @throws TaskDeletionException
     * @throws TaskDeleteCompletedException
     */
    public function removeTask(Task $task): void
    {
        if ($task->getTasks()->count() != 0) {
            throw new TaskDeletionException();
        }

        if ($task->getStatus() == TaskStatusConstant::STATUS_DONE) {
            throw new TaskDeleteCompletedException();
        }

        $this->remove($task);
    }

    /**
     * @throws TaskCompletionException
     */
    public function completeTask($task): void
    {
        foreach ($task->getTasks() as $subtask) {
            if ($subtask->getStatus() === TaskStatusConstant::STATUS_TODO) {
                throw new TaskCompletionException();
            }
        }
        $this->update(
            $task
                ->setStatus(TaskStatusConstant::STATUS_DONE)
                ->setCompletedAt(CarbonImmutable::now())
        );
    }
}
