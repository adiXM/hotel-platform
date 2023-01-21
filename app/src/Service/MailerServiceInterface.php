<?php

namespace App\Service;

interface MailerServiceInterface
{
    public function sendEmail(string $to,string  $subject,string $body, string $from = null): void;
}