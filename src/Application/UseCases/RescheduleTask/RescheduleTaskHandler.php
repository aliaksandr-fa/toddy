<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\RescheduleTask;


use Toddy\Domain\Task\TaskId;
use Toddy\Domain\Task\TaskRepositoryInterface;

/**
 * Class RescheduleTaskHandler
 * @package Toddy\Application\UseCases\RescheduleTask
 * @author Faley Aliaksandr
 */
class RescheduleTaskHandler
{

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * CreateTaskHandler constructor.
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param RescheduleTaskCommand $command
     * @throws \Exception
     */
    public function handle(RescheduleTaskCommand $command): void
    {
        $task = $this->taskRepository->getById(new TaskId($command->taskId));

        if (null === $command->dueDate) {
            $task->unschedule();
        } else {
            $task->schedule($command->dueDate);
        }

        $this->taskRepository->save($task);
    }
}