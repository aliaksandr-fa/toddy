<?php declare(strict_types=1);

namespace Toddy\Infrastructure\CommandBus;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class ValidationMiddleware
 * @package Toddy\Infrastructure\CommandBus
 * @author Faley Aliaksandr
 */
class ValidationMiddleware implements MessageBusMiddleware
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * ValidationMiddleware constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param object $message
     * @param callable $next
     */
    public function handle($message, callable $next): void
    {
        $violations = $this->validator->validate($message);

        if ($violations->count()) {

            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidationException($errors);
        }

        $next($message);
    }
}
