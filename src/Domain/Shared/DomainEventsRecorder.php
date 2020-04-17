<?php declare(strict_types=1);

namespace Toddy\Domain\Shared;

use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * Trait DomainEventsRecorder
 * @package Toddy\Domain\Shared
 * @author Faley Aliaksandr
 */
trait DomainEventsRecorder
{
    use PrivateMessageRecorderCapabilities;

    /**
     * @param mixed $message
     */
    public function recordOnce($message): void
    {
        if (in_array($message, $this->messages)) {
            return;
        }

        $this->record($message);
    }
}