<?php declare(strict_types=1);

namespace Toddy\Tests\Application\UseCases;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Toddy\Application\UseCases\CompleteTask\CompleteTaskCommand;
use Toddy\Application\UseCases\CompleteTask\CompleteTaskHandler;
use Toddy\Application\UseCases\RescheduleTask\RescheduleTaskCommand;
use Toddy\Application\UseCases\RescheduleTask\RescheduleTaskHandler;
use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskRepositoryInterface;


/**
 * Class CompleteTaskCommandHandlerTest
 * @package Toddy\Tests\Application\UseCases
 * @author Faley Aliaksandr
 */
class RescheduleTaskCommandHandlerTest extends TestCase
{
    public function testHandlerSchedulesTask()
    {
        $dueDate = new \DateTimeImmutable();
        $command = new RescheduleTaskCommand(
            Uuid::uuid4()->toString(),
            $dueDate
        );

        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $task = $this->getMockBuilder(Task::class)->disableOriginalConstructor()->getMock();

        $task
            ->expects($this->once())
            ->method('schedule')
            ->with($dueDate)
        ;

        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $taskRepositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)->getMock();

        $taskRepositoryMock->method('getById')->willReturn($task);

        $taskRepositoryMock
            ->expects($this->once())
            ->method('save');

        $handler = new RescheduleTaskHandler($taskRepositoryMock);
        $handler->handle($command);
    }

    public function testHandlerUnschedulesTask()
    {
        $command = new RescheduleTaskCommand(
            Uuid::uuid4()->toString(),
            null
        );

        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $task = $this->getMockBuilder(Task::class)->disableOriginalConstructor()->getMock();

        $task
            ->expects($this->once())
            ->method('unschedule')
        ;

        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $taskRepositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)->getMock();

        $taskRepositoryMock->method('getById')->willReturn($task);

        $taskRepositoryMock
            ->expects($this->once())
            ->method('save');

        $handler = new RescheduleTaskHandler($taskRepositoryMock);
        $handler->handle($command);
    }
}