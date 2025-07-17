<?php

namespace App\Enum;

enum NotificationStatusEnum: string
{

    case  SENT = 'sent';
    case  FAILED = 'failed';

    case PENDING = 'pending';

    case READY = 'ready';
}
