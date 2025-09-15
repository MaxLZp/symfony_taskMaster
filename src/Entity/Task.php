<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    public const STATUS_TODO = 'todo';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REVIEW = 'review';
    public const STATUS_COMPLETED = 'completed';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_CRITICAL = 'critical';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Task title cannot be empty')]
    #[Assert\Length(max: 255, message: 'Title cannot be longer than {{limit}} characters')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 5000, message: 'Description cannot be longer than {{limit}} characters')]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: [self::STATUS_TODO, self::STATUS_IN_PROGRESS, self::STATUS_REVIEW, self::STATUS_COMPLETED])]
    private ?string $status = self::STATUS_TODO;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: [self::PRIORITY_LOW, self::PRIORITY_MEDIUM, self::PRIORITY_HIGH, self::PRIORITY_CRITICAL])]
    private ?string $priority = self::PRIORITY_MEDIUM;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dueDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn('nullable')]
    #[Assert\NotNull(message: 'Task must belog to a project')]
    private ?Project $project = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeImmutable $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;
        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->getStatus() === Task::STATUS_COMPLETED;
    }

    public function isOverdue(): bool
    {
        return $this->dueDate && $this->dueDate < new \DateTimeImmutable() && !$this->isCompleted();
    }

    public function getDaysUntilDue(): ?int
    {
        if (!$this->dueDate) { return null;}
        $now = new \DateTimeImmutable();
        return $now->diff($this->dueDate)->days * ($this->isOverdue() ? -1 : 1);
    }

    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_CRITICAL => 'Critical',
            default => 'Unknown'
        };
    }

    public function getPriorityColor(): string
    {
        return match($this->getPriority()) {
            self::PRIORITY_LOW => 'success',
            self::PRIORITY_MEDIUM => 'warning',
            self::PRIORITY_HIGH => 'danger',
            self::PRIORITY_CRITICAL => 'dark',
            default => 'secondary'
        };
    }


    public function getStatusLabel(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_CRITICAL => 'Critical',
            default => 'Unknown'
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_TODO => 'secondary',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_REVIEW => 'warning',
            self::STATUS_COMPLETED => 'success',
            default => 'light'
        };
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
