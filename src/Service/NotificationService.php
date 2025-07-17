<?php
namespace App\Service;

use App\Entity\Notification;
use App\Entity\UserNotificationPreference;
use App\Message\NotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationService
{
    private $entityManager;
    private $messageBus;
    private $translator;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        TranslatorInterface $translator
    ) {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        $this->translator = $translator;
    }

    public function createNotification(int $userId, string $type, string $messageKey, array $parameters = []): void
    {
        $preference = $this->entityManager->getRepository(UserNotificationPreference::class)
            ->findOneBy(['userId' => $userId]);

        if (!$preference || !in_array($type, $preference->getNotificationTypes())) {
            return;
        }

        $notification = new Notification();
        $notification->setUserId($userId)
            ->setType($type)
            ->setChannel($preference->getChannel())
            ->setMessage($this->translator->trans($messageKey, $parameters, 'notifications', $preference->getLanguage()));

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        if ($preference->getFrequency() === 'IMMEDIATE') {
            $this->messageBus->dispatch(new NotificationMessage($notification->getId()));
        }
        // DAILY and WEEKLY notifications are processed by the command
    }
}