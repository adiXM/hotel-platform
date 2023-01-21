<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService implements MailerServiceInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function sendEmail(string $to,string $subject,string $body, string $from = null): void
    {
        if($from === null) {
            $from = 'adrianmarian906@gmail.com';
        }
        try {
            $email = (new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->text($body);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $ex) {
            dd($ex);
        }

    }
}