<?php
namespace App\MessageHandler;

use App\Channel\NotificationChannelFactory;
use App\Entity\Notification;
use App\Enum\NotificationStatusEnum;
use App\Message\NotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationMessageHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $channelFactory;

    private $logger;

    /*
     *  notication_user_{id}
     * Echo.private('')
     * Echo.public
     *
     * */


    public function __construct(EntityManagerInterface $entityManager, NotificationChannelFactory $channelFactory, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->channelFactory = $channelFactory;
        $this->logger =$logger;
    }

    public function __invoke(NotificationMessage $message)
    {
        $notification = $this->entityManager->getRepository(Notification::class)
            ->find($message->getNotificationId());

        if (!$notification) {
            return;
        }

        try {
            $channels = $this->channelFactory->createChannels($notification);
            foreach ($channels as $channel) {
                $channel->send($notification);
            }
            $notification->setStatus(NotificationStatusEnum::SENT->value);
        } catch (\Exception $e) {
            //Use logger to log failed
            $this->logger->error('[NOTIFICATION][ERROR]',[
                'excpetion' => $e,
                'notification_payload' => $notification
            ]);
            $notification->setStatus(NotificationStatusEnum::FAILED->value);
        }

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}