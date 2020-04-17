<?php declare(strict_types=1);

namespace Toddy\Tests\Application\UseCases;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Toddy\Application\UseCases\CompleteTask\CompleteTaskCommand;
use Toddy\Application\UseCases\CompleteTask\CompleteTaskHandler;
use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskRepositoryInterface;


/**
 * Class CompleteTaskCommandHandlerTest
 * @package Toddy\Tests\Application\UseCases
 * @author Faley Aliaksandr
 */
class CompleteTaskCommandHandlerTest extends TestCase
{
    public function testHandlerCompletesTask()
    {
        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $task = $this->getMockBuilder(Task::class)->disableOriginalConstructor()->getMock();

        $task
            ->expects($this->once())
            ->method('complete');

        /** @var TaskRepositoryInterface $taskRepositoryMock */
        $taskRepositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)->getMock();

        $taskRepositoryMock->method('getById')->willReturn($task);

        $taskRepositoryMock
            ->expects($this->once())
            ->method('save');

        $handler = new CompleteTaskHandler($taskRepositoryMock);
        $handler->handle(new CompleteTaskCommand(Uuid::uuid4()->toString()));
    }
}