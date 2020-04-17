<?php declare(strict_types=1);

namespace Toddy\Tests\Application\UseCases;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Toddy\Application\UseCases\CreateTask\CreateTaskCommand;
use Toddy\Application\UseCases\CreateTask\CreateTaskHandler;
use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskRepositoryInterface;
use Toddy\Domain\User\User;
use Toddy\Domain\User\UserId;
use Toddy\Domain\User\UserRepositoryInterface;


class CreateTaskCommandHandlerTest extends TestCase
{
    public function testHandlerCallsRepository()
    {
        $command          = new CreateTaskCommand();
        $command->title   = 'New todo.';
        $command->userId  = Uuid::uuid4()->toString();
        $command->dueDate = (new \DateTimeImmutable())->setTime(23, 59, 59);

        $user = new User(UserId::next(), 'test');


        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $taskRepositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)->getMock();

        /** @var UserRepositoryInterface $userRepositoryMock */
        $userRepositoryMock = $this->getMockBuilder(UserRepositoryInterface::class)->getMock();
        $userRepositoryMock
            ->method('getById')
            ->willReturn($user);

        $taskRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Task $task) use ($command, $user) {
                return
                    $task->getTitle() === $command->title
                    && $task->getDueDate()->getTimestamp() === $command->dueDate->getTimestamp()
                    && $task->getOwner() === $user;
            }));

        $handler = new CreateTaskHandler($userRepositoryMock, $taskRepositoryMock);
        $handler->handle($command);
    }
}