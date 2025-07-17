<?php

namespace App\Channel;
use App\Entity\Notification;

interface NotificationChannelInterface
{
    public function send(Notification $notification): void;

}