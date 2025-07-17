<?php
namespace App\Message;

class NotificationMessage
{
    private $notificationId;

    public function __construct(int $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): int
    {
        return $this->notificationId;
    }
}