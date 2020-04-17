<?php declare(strict_types=1);

namespace Toddy\Domain\Task;

use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use Toddy\Domain\Shared\DomainEventsRecorder;
use Toddy\Domain\Task\Events\TaskCompleted;
use Toddy\Domain\Task\Events\TaskCreated;
use Toddy\Domain\Task\Events\TaskReassigned;
use Toddy\Domain\Task\Events\TaskScheduled;
use Toddy\Domain\Task\Events\TaskUnscheduled;
use Toddy\Domain\Task\Exception\TaskException;
use Toddy\Domain\User\User;


/**
 * Class Task
 * @package Toddy\Domain
 * @author Faley Aliaksandr
 */
class Task implements ContainsRecordedMessages, \JsonSerializable
{
    use DomainEventsRecorder;

    /**
     * @var TaskId
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var User
     */
    protected $owner;

    /**
     * @var \DateTimeImmutable|null
     */
    protected $dueDate = null;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var \DateTimeImmutable|null
     */
    protected $completedAt;

    /**
     * Task constructor.
     * @param TaskId $id
     * @param string $title
     * @param User $owner
     * @param \DateTimeImmutable|null $dueDate
     * @throws \Exception
     */
    public function __construct(
        TaskId $id,
        string $title,
        User $owner,
        \DateTimeImmutable $dueDate = null
    ) {

        $this->id        = $id;
        $this->title     = $title;
        $this->owner     = $owner;
        $this->createdAt = new \DateTimeImmutable();

        $this->record(new TaskCreated($this));

        if (null !== $dueDate) {
            $this->schedule($dueDate);
        }
    }

    /**
     * @return TaskId
     */
    public function getId(): TaskId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->dueDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    /**
     * @param string $newTitle
     */
    public function rename(string $newTitle): void
    {
        $this->title = $newTitle;
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new TaskException("Task is already completed.");
        }

        $this->completedAt = new \DateTimeImmutable();

        $this->recordOnce(new TaskCompleted($this));
    }

    /**
     * @param \DateTimeImmutable $date
     * @throws \Exception
     */
    public function schedule(\DateTimeImmutable $date): void
    {
        if ($this->isCompleted()) {
            throw new TaskException("Completed task can't be scheduled.");
        }

        // rewind due date to the end of the day
        $date = $date->setTime(23, 59, 59);

        if ($date < new \DateTimeImmutable()) {
            throw new TaskException("Unable to schedule task for the past.");
        }

        $this->dueDate = $date;

        $this->recordOnce(new TaskScheduled($this));
    }

    /**
     * Removes due date
     */
    public function unschedule(): void
    {
        if (null !== $this->dueDate) {

            $this->dueDate = null;

            $this->recordOnce(new TaskUnscheduled($this));
        }
    }

    /**
     * @param User $newOwner
     */
    public function reassignTo(User $newOwner): void
    {
        $previousOwner = $this->owner;

        if ($previousOwner !== $newOwner) {

            $this->owner = $newOwner;
            $this->record(new TaskReassigned($this, $previousOwner));
        }
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completedAt !== null;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return !$this->isCompleted();
    }

    /**
     * @return bool
     */
    public function isScheduled(): bool
    {
        return $this->dueDate !== null;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id->getValue(),
            'title' => $this->title,
            'completed' => $this->isCompleted(),
            'due_date' => $this->dueDate ? $this->dueDate->format('Y-m-d') : null,
            'owner' => $this->owner->getId()->getValue()
        ];
    }
}