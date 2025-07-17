<?php

namespace App\Channel\SMS\Providers;

interface SmsInterface
{
    public function sendSms(array $payload, string $phoneNumber);
}