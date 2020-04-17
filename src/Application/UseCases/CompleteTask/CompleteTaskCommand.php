<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\CompleteTask;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class CompleteTaskCommand
 * @package Toddy\Application\UseCases\CompleteTask
 * @author Faley Aliaksandr
 */
class CompleteTaskCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     *
     * @var string
     */
    public $taskId;

    /**
     * CompleteTaskCommand constructor.
     * @param string $taskId
     */
    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }
}