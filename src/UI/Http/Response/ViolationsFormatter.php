<?php declare(strict_types=1);

namespace Toddy\UI\Http\Response;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ErrorFormatter
 * @package Toddy\UI\Http\Response
 * @author Faley Aliaksandr
 */
class ViolationsFormatter
{
    /**
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     * @return string[]
     */
    public function fromViolations(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errors[$violation->getMessage()] = $violation->getPropertyPath();
        }

        return $errors;
    }
}