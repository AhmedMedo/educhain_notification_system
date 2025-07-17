<?php
namespace App\Command;

use App\Entity\Notification;
use App\Entity\UserNotificationPreference;
use App\Message\NotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessDigestNotificationsCommand extends Command
{
    protected static $defaultName = 'app:process-digest-notifications';

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $frequencies = ['DAILY', 'WEEKLY'];
        $users = $this->entityManager->getRepository(UserNotificationPreference::class)
            ->findBy(['frequency' => $frequencies]);

        foreach ($users as $user) {
            $isWeekly = $user->getFrequency() === 'WEEKLY';
            $interval = $isWeekly ? new \DateInterval('P7D') : new \DateInterval('P1D');
            $startDate = (new \DateTime())->sub($interval);

            $notifications = $this->entityManager->getRepository(Notification::class)
                ->createQueryBuilder('n')
                ->where('n.userId = :userId')
                ->andWhere('n.createdAt >= :startDate')
                ->andWhere('n.status = :status')
                ->setParameter('userId', $user->getUserId())
                ->setParameter('startDate', $startDate)
                ->setParameter('status', 'PENDING')
                ->getQuery()
                ->getResult();

            if ($notifications) {
                foreach ($notifications as $notification) {
                    $this->messageBus->dispatch(new NotificationMessage($notification->getId()));
                }
                $output->writeln("Dispatched " . count($notifications) . " notifications for user {$user->getUserId()}");
            }
        }

        return Command::SUCCESS;
    }
}