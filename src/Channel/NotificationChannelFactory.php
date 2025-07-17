<?php
namespace App\Channel;

use App\Entity\Notification;
use InvalidArgumentException;

class NotificationChannelFactory
{
    private $inAppChannel;
    private $emailChannel;
    private $smsChannel;

    public function __construct(InAppChannel $inAppChannel, EmailChannel $emailChannel, SMSChannel $smsChannel)
    {
        $this->inAppChannel = $inAppChannel;
        $this->emailChannel = $emailChannel;
        $this->smsChannel = $smsChannel;
    }

    /**
     * @return NotificationChannelInterface[]
     */
    public function createChannels(Notification $notification): array
    {
        $channel = $notification->getChannel();
        switch ($channel) {
            case 'INAPP':
                return [$this->inAppChannel];
            case 'EMAIL':
                return [$this->emailChannel];
            case 'SMS':
                return [$this->smsChannel];
            case 'INAPP+EMAIL':
                return [$this->inAppChannel, $this->emailChannel];
            case 'INAPP+SMS':
                return [$this->inAppChannel, $this->smsChannel];
            case 'EMAIL+SMS':
                return [$this->emailChannel, $this->smsChannel];
            case 'INAPP+EMAIL+SMS':
                return [$this->inAppChannel, $this->emailChannel, $this->smsChannel];
            default:
                throw new InvalidArgumentException("Invalid channel: $channel");
        }
    }
}