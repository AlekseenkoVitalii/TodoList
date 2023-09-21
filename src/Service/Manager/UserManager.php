<?php

namespace App\Service\Manager;

use App\Entity\User;
use App\Exception\DuplicateDataException;
use App\Service\BaseManager;

class UserManager extends BaseManager
{
    /**
     * @throws DuplicateDataException
     */
    public function saveUser(User $user): User
    {
        $existingUser = $this->getRepo(User::class)->findOneBy(['email' => $user->getEmail()]);

        if ($existingUser) {
            throw new DuplicateDataException();
        }

        $this->save($user);

        return $user;

    }
}
