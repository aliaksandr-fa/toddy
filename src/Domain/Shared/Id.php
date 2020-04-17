<?php declare(strict_types=1);

namespace Toddy\Domain\Shared;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;


/**
 * Trait Id
 *
 * @package Parser\Domain
 * @author Faley Aliaksandr
 */
trait Id
{
    /**
     * @var string
     */
    private $value;

    /**
     * Id constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::uuid($value);

        $this->value = $value;
    }

    /**
     * @return self
     * @throws \Exception
     */
    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
