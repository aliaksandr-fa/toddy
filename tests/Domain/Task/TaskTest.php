<?php declare(strict_types=1);

namespace Toddy\Tests\Domain\Task;

use PHPUnit\Framework\TestCase;
use Toddy\Domain\Task\Events\TaskCompleted;
use Toddy\Domain\Task\Events\TaskCreated;
use Toddy\Domain\Task\Events\TaskReassigned;
use Toddy\Domain\Task\Events\TaskScheduled;
use Toddy\Domain\Task\Events\TaskUnscheduled;
use Toddy\Domain\Task\Exception\TaskException;
use Toddy\Domain\Task\Task;
use Toddy\Domain\Task\TaskId;
use Toddy\Domain\User\User;
use Toddy\Domain\User\UserId;
use Toddy\Tests\Domain\CustomAsserts;


/**
 * Class TaskTest
 * @package Toddy\Tests\Domain\Task
 * @author Faley Aliaksandr
 */
class TaskTest extends TestCase
{
    use CustomAsserts;

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var
     */
    protected $newUser;

    public function setUp()
    {
        $this->user = new User(UserId::next(), 'user1');
        $this->newUser = new User(UserId::next(), 'user2');

        $this->task = new Task(TaskId::next(), 'Task to complete', $this->user);
    }

    public function testTaskRecordsCreatedEvent() {
        $this->assertContainsInstancesOf(
            TaskCreated::class, $this->task->recordedMessages(),
            'Task didn\'t recorded created event.'
        );
    }

    public function testTaskCanBeCompleted()
    {
        $this->task->complete();

        $this->assertTrue($this->task->isCompleted(), 'Task failed to complete.');
        $this->assertNotEmpty($this->task->getCompletedAt());
        $this->assertContainsInstancesOf(
            TaskCompleted::class, $this->task->recordedMessages(),
            'Task didn\'t recorded completed event.'
        );
    }

    public function testTaskCanBeScheduled()
    {
        $this->task->schedule(new \DateTimeImmutable());

        $this->assertTrue($this->task->isScheduled(), 'Failed to schedule a task.');
        $this->assertNotEmpty($this->task->getDueDate());
        $this->assertContainsInstancesOf(
            TaskScheduled::class, $this->task->recordedMessages(),
            'Task didn\'t recorded scheduled event.'
        );
    }

    public function testTaskCannotBeScheduledForYesterday() {

        $this->expectException(TaskException::class);

        $dueDate = (new \DateTimeImmutable())->modify('-1 day');
        $this->task->schedule($dueDate);
    }

    public function testTaskCanBeUnScheduled()
    {
        $this->task->schedule(new \DateTimeImmutable());
        $this->assertTrue($this->task->isScheduled(), 'Failed to schedule a task.');
        $this->assertNotEmpty($this->task->getDueDate());

        $this->task->unschedule();
        $this->assertEmpty($this->task->getDueDate());
        $this->assertContainsInstancesOf(
            TaskUnscheduled::class, $this->task->recordedMessages(),
            'Task didn\'t recorded unscheduled event.'
        );
    }

    public function testTaskCanBeReassigned()
    {
        $this->task->reassignTo($this->newUser);

        $this->assertEquals($this->newUser, $this->task->getOwner(), 'Reassigned user differs from initial one.');
        $this->assertContainsInstancesOf(
            TaskReassigned::class, $this->task->recordedMessages(),
            'Task didn\'t recorded reassign event.'
        );
    }
}