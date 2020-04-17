<?php declare(strict_types=1);

namespace Toddy\Domain\Task;

use Toddy\Domain\Task\Exception\TaskNotFoundException;
use Toddy\Domain\User\User;

/**
 * Interface TaskRepositoryInterface
 * @package Toddy\Domain\Task
 * @author Faley Aliaksandr
 */
interface TaskRepositoryInterface
{
    /**
     * @param TaskId $taskId
     * @return Task
     * @throws TaskNotFoundException
     */
    public function getById(TaskId $taskId): Task;

    /**
     * @param Task $task
     */
    public function save(Task $task): void;

    /**
     * @param Task $task
     */
    public function remove(Task $task): void;

    /**
     * @param User $user
     * @return Task[]
     */
    public function getByUser(User $user): array;

    /**
     * @param User $user
     * @param \DateTimeImmutable|null $scheduledAt
     * @return Task[]
     */
    public function getScheduledByUser(User $user, \DateTimeImmutable $scheduledAt = null): array;
}