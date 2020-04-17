<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Debug;

use Psr\Log\LoggerInterface;
use Toddy\Domain\Task\Events\TaskCompleted;
use Toddy\Domain\Task\Events\TaskCreated;
use Toddy\Domain\Task\Events\TaskReassigned;
use Toddy\Domain\Task\Events\TaskScheduled;
use Toddy\Domain\Task\Events\TaskUnscheduled;


/**
 * Class TraceEventsListener
 * @package Toddy\Infrastructure\Debug
 * @author Faley Aliaksandr
 */
class TraceEventsListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onTaskCreated(TaskCreated $event): void
    {
        $this->logger->info('TaskCreated', $event->getTask()->jsonSerialize());
    }

    public function onTaskCompleted(TaskCompleted $event): void
    {
        $this->logger->info('TaskCompleted', $event->getTask()->jsonSerialize());
    }

    public function onTaskScheduled(TaskScheduled $event): void
    {
        $this->logger->info('TaskScheduled', $event->getTask()->jsonSerialize());
    }

    public function onTaskUnscheduled(TaskUnscheduled $event): void
    {
        $this->logger->info('TaskUnscheduled', $event->getTask()->jsonSerialize());
    }

    public function onTaskReassigned(TaskReassigned $event): void
    {
        $this->logger->info('TaskUnscheduled', [
            'event' => $event->getTask()->jsonSerialize(),
            'previous_owner' => $event->getPreviousOwner()
        ]);
    }
}