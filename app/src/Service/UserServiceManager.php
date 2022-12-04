<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Use repository for fetch data
 * User entity manager for persist, flush..etc
 */
class UserServiceManager
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $repository
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    public function getUserList(): array
    {
        return $this->repository->findAllWithFields('u', ['u.id', 'u.email', 'u.roles', 'u.firstname', 'u.lastname']);
    }

    public function updateUser(User $userData): void
    {
        $this->entityManager->persist($userData);
        $this->entityManager->flush();
    }

    public function getHashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

}