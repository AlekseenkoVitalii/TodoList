<?php

namespace App\Controller\api;

use App\Controller\BaseController;
use App\Entity\User;
use App\Exception\TaskCompletionException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Task;
use App\Form\TaskType;
use App\Service\Manager\TaskManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
class TaskController extends BaseController
{
    #[Route('/create', methods: 'POST')]
    public function createTask(Request $request, TaskManager $taskManager): Response
    {
        return $this->handleTask($request, $taskManager, $taskManager->newEntity(Task::class));
    }

    #[Route('/{id}', methods: 'GET')]
    public function getOneTask(TaskManager $taskManager, int $id): Response
    {
        return  $this->sendJson($this->getTask($taskManager, $id));
    }

    #[Route('', methods: 'GET')]
    public function getAllTasks(TaskManager $taskManager): Response
    {
        return $this->sendJson($taskManager->getTasksAll($this->getUserTest($taskManager)));
    }

    #[Route('/update/{id}', methods: 'PATCH')]
    public function updateTask(Request $request, TaskManager $taskManager, int $id): Response
    {
        $data = $this->getTask($taskManager, $id);

        return array_key_exists('data', $data)
            ? $this->handleTask($request, $taskManager, $data['data'])
            : $this->sendJson($data);
    }

    #[Route('/remove/{id}', methods: 'DELETE')]
    public function removeTask(TaskManager $taskManager, int $id): Response
    {
        $data = $this->getTask($taskManager, $id);

        if (array_key_exists('data', $data)) {
            try {
                $taskManager->removeTask($data['data']);
                $data['data'] = null;
            } catch (Exception $exception) {

                return $this->sendJson(['error' => $exception->getMessage()]);
            }
        }

        return $this->sendJson($data);
    }

    #[Route('/close/{id}', methods: 'GET')]
    public function completeTask(TaskManager $taskManager, int $id): Response
    {
        $data = $this->getTask($taskManager, $id);

        if (array_key_exists('data', $data)) {
            try {
                $taskManager->completeTask($data['data']);
            } catch (TaskCompletionException $e) {

                return $this->sendJson(['error' => $e->getMessage()]);
            }
        }

        return $this->sendJson($data);
    }

    protected function handleTask(Request $request, TaskManager $taskManager, Task $task): Response
    {
        $user = $this->getUserTest($taskManager);

        $form = $this->createForm(TaskType::class, $task, [
            'method' => $request->getMethod(),
        ]);

        $form->submit(json_decode($request->getContent(), true));
        $form->getData()->setExecutor($user);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $taskManager->save($form->getData());
            } catch (Exception $exception) {

                return $this->sendJson(['error' => $exception->getMessage()]);
            }

            return $this->sendJson(['data' => $form->getData()]);
        }

        return $this->sendJson(['error' => $form]);
    }

    protected function getTask(TaskManager $taskManager, int $id): array
    {
        $user = $this->getUserTest($taskManager);

        try {
            $task = $taskManager->getTaskOne($id, $user);
        } catch (Exception $exception) {

            return ['error' => $exception->getMessage()];
        }

        return ['data' => $task];
    }










    private function getFormErrors( $form)
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
                $childErrors = $this->getFormErrors($childForm);
                if (!empty($childErrors)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
        }

        return $errors;
    }

    protected function getUserTest(TaskManager$taskManager): User
    {
        return $taskManager->getRepo(User::class)->find(1);
    }
}
