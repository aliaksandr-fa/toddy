<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\RescheduleTask;


/**
 * Class RescheduleTaskCommand
 * @package Toddy\Application\UseCases\RescheduleTask
 * @author Faley Aliaksandr
 */
class RescheduleTaskCommand
{
    /**
     *
     * @var string
     */
    public $taskId;

    /**
     * @var \DateTimeImmutable|null
     */
    public $dueDate;

    /**
     * RescheduleTaskCommand constructor.
     * @param string $taskId
     * @param \DateTimeImmutable|null $dueDate
     */
    public function __construct(string $taskId, ?\DateTimeImmutable $dueDate = null)
    {
        $this->taskId  = $taskId;
        $this->dueDate = $dueDate;
    }
}