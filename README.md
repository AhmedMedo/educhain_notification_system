# Educhain Notification System

## Overview
This project implements a robust, extensible notification system for a Symfony application. It supports multiple notification channels (In-App, Email, SMS), user preferences, translation, and both immediate and scheduled (digest) notifications. The system is designed for asynchronous processing and easy integration of new channels/providers.

---

## Architecture & Patterns

### 1. **Notification Channels (Strategy Pattern)**
- **Interface:** `NotificationChannelInterface` defines a `send(Notification $notification): void` method.
- **Implementations:**
  - `InAppChannel`: Persists notifications in the database.
  - `EmailChannel`: Sends notifications via email using Symfony Mailer.
  - `SMSChannel`: Sends notifications via SMS using a provider selected at runtime.
- **Factory:** `NotificationChannelFactory` selects and returns the appropriate channel(s) based on the notification's `channel` property. Supports multi-channel delivery (e.g., `INAPP+EMAIL+SMS`).

### 2. **SMS Provider Abstraction (Factory + Strategy)**
- **Interface:** `SmsInterface` defines `sendSms(array $payload, string $phoneNumber)`.
- **Providers:**
  - `Twilio\Client`
  - `Vonage\Client`
- **Factory:** `SmsFactory::loadSmsClient()` selects the provider based on the `SMS_PROVIDER` environment variable (`twilio` or `vonage`).
- **Extension:** Add new providers by implementing `SmsInterface` and updating the factory.

### 3. **Notification Creation & Dispatch (Service + CQRS)**
- **Service:** `NotificationService` creates notifications based on user preferences, translates messages, and dispatches them to the message bus for async processing if immediate, or leaves them for scheduled processing.
- **Message:** `NotificationMessage` carries the notification ID for async handling.
- **Handler:** `NotificationMessageHandler` loads the notification, uses the channel factory, sends via all required channels, and updates status (SENT/FAILED).

### 4. **Scheduled & Failed Notification Processing (Command Pattern)**
- **Commands:**
  - `ProcessDigestNotificationsCommand`: Finds users with `DAILY` or `WEEKLY` preferences, dispatches their pending notifications for the relevant period.
  - `ProcessFailedNotificationsCommand`: Finds notifications with status `FAILED` and retries them.
- **Usage:** Run these commands via cron or Symfony scheduler.

---

## Configuration

### Environment Variables
- `SMS_PROVIDER`: Set to `twilio` or `vonage` to select the SMS provider.

### Example `.env` entry:
```
SMS_PROVIDER=twilio
```

---

## Extending the System

- **Add a new channel:**
  1. Implement `NotificationChannelInterface`.
  2. Register your channel in `NotificationChannelFactory`.
- **Add a new SMS provider:**
  1. Implement `SmsInterface`.
  2. Update `SmsFactory` to return your provider based on config.
- **Add new notification types or preferences:**
  - Update `UserNotificationPreference` and related logic.

---

## Notification Flow Diagram

```
User Action/Trigger
      |
      v
NotificationService::createNotification
      |
      v
Persist Notification Entity
      |
      v
Immediate? ----> Yes ----> Dispatch NotificationMessage to Bus
      |                          |
      |                          v
      |                NotificationMessageHandler
      |                          |
      |                          v
      |                ChannelFactory -> [Channels]
      |                          |
      |                          v
      |                Channel::send (InApp/Email/SMS)
      |                          |
      |                          v
      |                Update Notification Status
      |
      +----> No (Digest)
                  |
                  v
        ProcessDigestNotificationsCommand (Scheduled)
                  |
                  v
        Dispatch NotificationMessage to Bus
```

---

## Running the Commands

- **Process Digest Notifications:**
  ```bash
  php bin/console app:process-digest-notifications
  ```
- **Process Failed Notifications:**
  ```bash
  php bin/console app:process-failed
  ```

---

## Notes
- The actual SMS sending logic for Twilio and Vonage is stubbed; implement in their respective client classes.
- Ensure your `Notification` entity provides a way to get the recipient's phone number for SMS.
- All notification sending is handled asynchronously for scalability and reliability.

---

## Contribution & Customization
- Fork, extend, and adapt the system for your needs.
- PRs and suggestions welcome! 