<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Booking;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Use repository for fetch data
 * User entity manager for persist, flush..etc
 */
class UserManagerService
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

    public function removeUser(string $userId): void
    {
        /** @var User $user */
        $user = $this->repository->find($userId);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function getHashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

}