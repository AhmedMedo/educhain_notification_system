<?php

namespace App\Channel;

use App\Entity\Notification;
use App\Channel\SMS\Providers\SmsFactory;

class SMSChannel implements NotificationChannelInterface
{

    public function send(Notification $notification): void
    {
        // Get the phone number from the notification or user entity as needed
        $phoneNumber = $notification->getPhoneNumber(); // Adjust as per your Notification entity
        $payload = [
            'message' => $notification->getMessage(),
            // Add other payload fields as needed
        ];

        // Get the SMS provider from config
        $smsClient = SmsFactory::loadSmsClient();
        if ($smsClient) {
            // This will use the configured provider (Twilio or Vonage)
            // For Twilio: // $smsClient->sendSms($payload, $phoneNumber); // Twilio logic here
            // For Vonage: // $smsClient->sendSms($payload, $phoneNumber); // Vonage logic here
        } else {
            // Log or handle missing provider
        }
    }
}