<?php declare(strict_types=1);

namespace Toddy\Application\UseCases\CreateTask;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class CreateTaskCommand
 * @package Toddy\Application\UseCases\CreateTask
 * @author Faley Aliaksandr
 */
class CreateTaskCommand
{
    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    public $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     *
     * @var string
     */
    public $userId;

    /**
     * @var \DateTimeImmutable|null
     */
    public $dueDate;
}