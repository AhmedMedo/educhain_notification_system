<?php

namespace App\Channel\SMS\Providers\Vonage;

use App\Channel\SMS\Providers\SmsInterface;

class Client implements SmsInterface
{

    public function sendSms(array $payload, string $phoneNumber)
    {
        // Implement Vonage SMS sending logic here
    }
}