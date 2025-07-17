<?php
namespace App\Channel;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class InAppChannel implements NotificationChannelInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function send(Notification $notification): void
    {
        try {
            $notification->setStatus('SENT');
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }catch (\Exception $exception )
        {

        }

    }
}