<?php
namespace App\Command;

use App\Entity\Notification;
use App\Entity\UserNotificationPreference;
use App\Enum\NotificationStatusEnum;
use App\Message\NotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessFailedNotificationsCommand extends Command
{
    protected static $defaultName = 'app:process-failed';

    private $entityManager;
    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    protected function configure()
    {
        $this->setDescription('Process daily and weekly digest notifications');
    }

    /**
     * this will be run every hour
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $notifications = $this->entityManager->getRepository(Notification::class)
            ->createQueryBuilder('n')
            ->andWhere('n.status = :status')
            ->setParameter('status', NotificationStatusEnum::FAILED->value)
            ->getQuery()
            ->getResult();

        foreach ($notifications as $notification) {
            $this->messageBus->dispatch(new NotificationMessage($notification->getId()));

        }

        return Command::SUCCESS;
    }
}