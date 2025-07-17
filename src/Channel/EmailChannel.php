<?php
namespace App\Channel;

use App\Entity\Notification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailChannel implements NotificationChannelInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Notification $notification): void
    {
        try {
            $email = (new Email())
                ->from('no-reply@educhchain.com')
                ->to('your-email@example.com')
                ->subject('Educhain Notification')
                ->text($notification->getMessage());

            $this->mailer->send($email);

        }catch (\Exception $exception)
        {
            //we will update the notifcaiton status to NotifcationStatusEnum::FAILED
        }

    }
}