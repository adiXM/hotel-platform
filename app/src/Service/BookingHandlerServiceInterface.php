<?php

namespace App\Service;

use App\Entity\Customer;

interface BookingHandlerServiceInterface
{
    public function handle(array $bookingData, Customer $customer): void;
}