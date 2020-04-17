<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Toddy\Domain\Task\Exception\TaskNotFoundException;
use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskId;
use Toddy\Domain\Task\TaskRepositoryInterface;
use Toddy\Domain\User\User;


/**
 * Class TaskRepository
 * @package Toddy\Infrastructure\Persistence\Doctrine
 * @author Faley Aliaksandr
 */
class TaskRepository implements TaskRepositoryInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param TaskId $taskId
     * @return Task
     * @throws TaskNotFoundException
     */
    public function getById(TaskId $taskId): Task
    {
        /** @var Task $task */
        $task = $this->entityManager->find(Task::class, $taskId);

        if (null == $task) {
            throw new TaskNotFoundException("Task with id $taskId not found.");
        }

        return $task;
    }

    /**
     * @param Task $task
     */
    public function save(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    /**
     * @param Task $task
     */
    public function remove(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @return Task[]
     */
    public function getByUser(User $user): array
    {
        return $this->entityManager
            ->getRepository(Task::class)
            ->findBy([
                'owner' => $user
            ]);
    }

    /**
     * @param User $user
     * @param \DateTimeImmutable|null $scheduledAt
     * @return Task[]
     */
    public function getScheduledByUser(User $user, \DateTimeImmutable $scheduledAt = null): array
    {
        $criteria = [
            'owner' => $user
        ];

        if (null !== $scheduledAt) {
            $criteria['dueDate'] = $scheduledAt->setTime(23, 59, 59);
        }

        return $this->entityManager
            ->getRepository(Task::class)
            ->findBy($criteria);
    }
}