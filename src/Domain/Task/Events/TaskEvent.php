<?php declare(strict_types=1);

namespace Toddy\Domain\Task\Events;

use Toddy\Domain\Task\Task;


/**
 * Class TaskEvent
 * @package Toddy\Domain\Task\Events
 * @author Faley Aliaksandr
 */
class TaskEvent
{
    /**
     * @var Task
     */
    protected $task;

    /**
     * TaskCompleted constructor.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }
}