<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\CompleteTask;

use Toddy\Domain\Task\TaskId;
use Toddy\Domain\Task\TaskRepositoryInterface;


/**
 * Class CompleteTaskHandler
 * @package Toddy\Application\UseCases\CompleteTask
 * @author Faley Aliaksandr
 */
class CompleteTaskHandler
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
     * @param CompleteTaskCommand $command
     */
    public function handle(CompleteTaskCommand $command): void
    {
        $task = $this->taskRepository->getById(new TaskId($command->taskId));

        $task->complete();

        $this->taskRepository->save($task);
    }
}