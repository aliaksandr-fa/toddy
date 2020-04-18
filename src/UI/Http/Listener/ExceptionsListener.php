<?php declare(strict_types=1);

namespace Toddy\UI\Http\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Toddy\Domain\Shared\DomainException;
use Toddy\Infrastructure\CommandBus\ValidationException;


/**
 * Class ExceptionsListener
 * @package Toddy\UI\Http\Listener
 * @author Faley Aliaksandr
 */
class ExceptionsListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DomainException) {

            $response = new JsonResponse([
                'errors' => $exception->getMessage()
            ], 409);

            $event->setResponse($response);
            return;
        }

        if ($exception instanceof ValidationException) {

            $response = new JsonResponse([
                'errors' => $exception->getMessages()
            ], 400);

            $event->setResponse($response);
            return;
        }

        if ($exception instanceof \RuntimeException) {

            $response = new JsonResponse([
                'errors' => [$exception->getMessage()]
            ], 500);

            $event->setResponse($response);
            return;
        }

    }
}