<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'notifications')]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $userId = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $type = null;

    #[ORM\Column(type: 'text')]
    private ?string $message = null;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $channel = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $status = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = 'PENDING';
    }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $allowedTypes = ['CREDENTIAL_ISSUED', 'TASK_COMPLETED', 'APPROVAL_REQUIRED', 'DOCUMENT_SHARED', 'REQUEST_APPROVED', 'REQUEST_REJECTED'];
        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid notification type: $type");
        }
        $this->type = $type;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $allowedStatuses = ['PENDING', 'SENT', 'FAILED'];
        if (!in_array($status, $allowedStatuses)) {
            throw new \InvalidArgumentException("Invalid status: $status");
        }
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}