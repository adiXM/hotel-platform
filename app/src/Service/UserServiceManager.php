<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;

class UserServiceManager
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    public function updateUser(mixed $userData): void
    {
        $this->entityManager->persist($userData);
        $this->entityManager->flush();
    }

    public function getHashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

}