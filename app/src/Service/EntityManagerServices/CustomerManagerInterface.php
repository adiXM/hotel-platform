<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Customer;

interface CustomerManagerInterface
{
    public function getCustomerList(): array;

    public function updateUser(Customer $customerData): void;

    public function removeCustomer(string $customerId): void;

    public function getHashPassword(Customer $customer, string $plainPassword): string;

}