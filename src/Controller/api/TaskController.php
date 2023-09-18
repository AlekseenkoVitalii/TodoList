<?php

namespace App\Controller\api;

use App\Controller\BaseController;
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
    public function create(Request $request, TaskManager $taskManager): Response
    {
        $task = $taskManager->newEntity(Task::class);

        $form = $this->createForm(TaskType::class, $task, [
            'method' => $request->getMethod(),
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $taskManager->save($form->getData());
            } catch (\Exception $exception) {
                return $this->handleView($this->view(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST));
            }

            return $this->handleView($this->view(['data' => $form->getData()], Response::HTTP_OK));
        }

        return $this->handleView($this->view($form, Response::HTTP_BAD_REQUEST));
    }

    #[Route('/{id}', methods: 'GET')]
    public function getOne(Task $task): Response
    {
        return $this->handleView($this->view(['data' => $task], Response::HTTP_OK));
    }

    #[Route('', methods: 'GET')]
    public function getAll(TaskManager $taskManager): Response
    {
        return $this->handleView($this->view(['data' => $taskManager->getTaskAll()], Response::HTTP_OK));
    }

    #[Route('/update/{id}', methods: 'PATCH')]
    public function update(Task $task)
    {
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
}
