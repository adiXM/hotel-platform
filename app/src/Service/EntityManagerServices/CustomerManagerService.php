<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerManagerService implements CustomerManagerInterface
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
        $customerCollection  = new ArrayCollection();

        $customers = $this->repository->findAll();
        foreach ($customers as $customer) {
            $customerCollection->add([
                'id' => $customer->getId(),
                'firstname' => $customer->getFirstName(),
                'lastname' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phone' => $customer->getPhone(),
            ]);
        }

        return $customerCollection->toArray();
    }

    public function updateUser(Customer $customerData): void
    {
        $this->entityManager->persist($customerData);
        $this->entityManager->flush();
    }

    public function removeCustomer(string $customerId): void
    {
        /** @var Customer $customer */
        $customer = $this->repository->find($customerId);
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }

    public function getHashPassword(Customer $customer, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($customer, $plainPassword);
    }
}