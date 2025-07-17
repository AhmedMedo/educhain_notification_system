<?php

namespace App\Channel\SMS\Providers\Twilio;

use App\Channel\SMS\Providers\SmsInterface;

class Client implements SmsInterface
{
    /**
     * we will use Twilio Client
     */

    public function sendSms(array $payload, string $phoneNumber)
    {
        // Implement Twilio SMS sending logic here
    }
}