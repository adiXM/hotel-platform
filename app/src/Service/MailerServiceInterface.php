<?php

namespace App\Service;

interface MailerServiceInterface
{
    public function sendEmail($to, $subject, $body): void;
}