<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\CreateTask;

use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskId;
use Toddy\Domain\Task\TaskRepositoryInterface;
use Toddy\Domain\User\UserId;
use Toddy\Domain\User\UserRepositoryInterface;


/**
 * Class CreateTaskHandler
 * @package Toddy\Application\UseCases\CreateTask
 * @author Faley Aliaksandr
 */
class CreateTaskHandler
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * CreateTaskHandler constructor.
     * @param UserRepositoryInterface $userRepository
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param CreateTaskCommand $command
     * @throws \Exception
     */
    public function handle(CreateTaskCommand $command): void
    {
        $user = $this->userRepository->getById(new UserId($command->userId));

        $task = new Task(
            TaskId::next(),
            $command->title,
            $user,
            $command->dueDate
        );

        $this->taskRepository->save($task);
    }
}