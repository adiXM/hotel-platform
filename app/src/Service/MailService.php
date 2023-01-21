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

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail($to, $subject, $body): void
    {
        try {
            $email = (new Email())
                ->from('adrianmarian906@gmail.com')
                ->to($to)
                ->subject($subject)
                ->text($body);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $ex) {
            dd($ex);
        }

    }
}