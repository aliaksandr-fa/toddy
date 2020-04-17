<?php declare(strict_types=1);

namespace Toddy\UI\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ListTasksRequest
 * @package Toddy\UI\Http\Request
 * @author Faley Aliaksandr
 */
class ListTasksRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     *
     * @var string
     */
    public $userId;

    /**
     * @Assert\Date()
     *
     * @var \DateTimeImmutable
     */
    public $dueDate;
}