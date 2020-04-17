<?php declare(strict_types=1);

namespace Toddy\Domain\Task\Events;


use Toddy\Domain\Task\Task;
use Toddy\Domain\User\User;

/**
 * Class TaskReassigned
 * @package Toddy\Domain\Task\Events
 * @author Faley Aliaksandr
 */
class TaskReassigned extends TaskEvent
{
    /**
     * @var User
     */
    protected $previousOwner;

    /**
     * TaskReassigned constructor.
     * @param Task $task
     * @param User $previousOwner
     */
    public function __construct(Task $task, User $previousOwner)
    {
        parent::__construct($task);

        $this->previousOwner = $previousOwner;
    }

    /**
     * @return User
     */
    public function getPreviousOwner(): User
    {
        return $this->previousOwner;
    }
}