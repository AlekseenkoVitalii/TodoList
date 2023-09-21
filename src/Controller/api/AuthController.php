<?php

namespace App\Controller\api;


use App\Controller\BaseController;
use App\Entity\User;
use App\Form\UserType;
use Exception;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    #[Route('register', methods: 'POST')]
   public function register(Request $request, UserManager $userManager): Response
   {
       $user = $userManager->newEntity(User::class);

       return $this->handleUser($request, $userManager, $user);
   }

    protected function handleUser(Request $request, UserManager $userManager, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'method' => $request->getMethod(),
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $userManager->saveUser($form->getData(), );
            } catch (Exception $exception) {

                return $this->sendJson(['error' => $exception->getMessage()]);
            }

            return $this->sendJson(['data' => $form->getData()]);
        }

        return $this->sendJson(['error' => $form]);
    }
}
