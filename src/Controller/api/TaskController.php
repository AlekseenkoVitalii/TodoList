<?php

namespace App\Controller\api;

use App\Controller\BaseController;
use App\Exception\TaskCompletionException;
use App\Form\OptionsType;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Task;
use App\Form\TaskType;
use App\Service\Manager\TaskManager;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;

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
    public function getAllTasksBy(Request $request, TaskManager $taskManager): Response
    {
        $parameters = ['executor' => $this->getUser()];
        $orderBy = [];

        foreach ($request->query->keys() as $parameter) {
            if ($request->get($parameter) && $request->get($parameter) != 'DESC' && $request->get($parameter) != 'ASC') {
                $parameters[$parameter] = $request->get($parameter);
            } else if ($request->get($parameter)) {
                $orderBy[$parameter] = $request->get($parameter);
            }
        }

        $form = $this->createForm(OptionsType::class, null, [
            'method' => $request->getMethod(),
        ]);
        $form->submit($parameters);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->sendJson($taskManager->getTasksAllBy($this->getUser(), $parameters, $orderBy));
        }

        return $this->sendJson(['error' => $form]);
    }

    #[Route('/update/{id}', methods: 'PATCH')]
    public function updateTask(Request $request, TaskManager $taskManager, int $id): Response
    {
        $data = $this->getTask($taskManager, $id);

        return array_key_exists('data', $data)
            ? $this->handleTask($request, $taskManager, $data['data']->setExecutor($this->getUser()))
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
        $form = $this->createForm(TaskType::class, $task, [
            'method' => $request->getMethod(),
        ]);

        $form->submit(json_decode($request->getContent(), true));
        $form->getData()->setExecutor($this->getUser());

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
        try {
            $task = $taskManager->getTaskOne($id, $this->getUser());
        } catch (Exception $exception) {

            return ['error' => $exception->getMessage()];
        }

        return ['data' => $task];
    }
}
