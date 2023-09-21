<?php

namespace App\Controller\api;


use App\Controller\BaseController;
use App\Entity\User;
use App\Exception\DuplicateDataException;
use App\Form\UserType;
use App\Service\Manager\UserManager;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends BaseController
{
    #[Route('register', methods: 'POST')]
   public function register(Request $request, UserPasswordHasherInterface $passwordHasher, UserManager $userManager): Response
   {
       $user = $userManager->newEntity(User::class);
       $form = $this->createForm(UserType::class, $user, [
           'method' => $request->getMethod(),
       ]);
       $form->submit(json_decode($request->getContent(), true));
       if ($form->isSubmitted() && $form->isValid()) {
           $hashedPassword = $passwordHasher->hashPassword(
               $user,
               $form->get('plainPassword')->getData()
           );

           try {
               $userManager->saveUser($user->setPassword($hashedPassword));
           } catch (DuplicateDataException $exception) {

               return $this->sendJson(['error' => $exception->getMessage()]);
           }

           return $this->sendJson(['data' => $user]);
       }

       return $this->sendJson(['error' => $form]);
   }
}
