<?php declare(strict_types=1);

namespace Toddy\Infrastructure\CommandBus;


/**
 * Class ValidationException
 * @package Toddy\Infrastructure\CommandBus
 * @author Faley Aliaksandr
 */
class ValidationException extends \RuntimeException
{
    /**
     * @var string[]
     */
    protected $messages = [];

    /**
     * ValidationException constructor.
     * @param string[] $messages
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(array $messages, int $code = 0, \Exception $previous = null)
    {
        $this->messages = $messages;

        parent::__construct(count($messages) ? reset($this->messages) : '', $code, $previous);
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}