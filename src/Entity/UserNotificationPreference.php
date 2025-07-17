<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_notification_preferences')]
class UserNotificationPreference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $userId = null;

    #[ORM\Column(type: 'json')]
    private array $notificationTypes = [];

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $channel = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $frequency = null;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getNotificationTypes(): array
    {
        return $this->notificationTypes;
    }

    public function setNotificationTypes(array $notificationTypes): self
    {
        $this->notificationTypes = $notificationTypes;
        return $this;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $allowedChannels = ['INAPP', 'EMAIL', 'BOTH'];
        if (!in_array($channel, $allowedChannels)) {
            throw new \InvalidArgumentException("Invalid channel: $channel");
        }
        $this->channel = $channel;
        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): self
    {
        $allowedFrequencies = ['IMMEDIATE', 'DAILY', 'WEEKLY'];
        if (!in_array($frequency, $allowedFrequencies)) {
            throw new \InvalidArgumentException("Invalid frequency: $frequency");
        }
        $this->frequency = $frequency;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
}