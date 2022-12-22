<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerManagerService
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly CustomerRepository $repository
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    public function getCustomerList(): array
    {
        return $this->repository->findAllWithFields('c', ['c.id', 'c.email', 'c.roles', 'c.firstname', 'c.lastname']);
    }

    public function updateUser(Customer $userData): void
    {
        $this->entityManager->persist($userData);
        $this->entityManager->flush();
    }

    public function getHashPassword(Customer $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}