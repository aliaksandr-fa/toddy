<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Ramsey\Uuid\Doctrine\UuidType;
use Toddy\Domain\Task\TaskId;


/**
 * Class TaskIdType
 * @package Toddy\Infrastructure\Persistence\Doctrine\Type
 * @author Faley Aliaksandr
 */
class TaskIdType extends UuidType
{
    const NAME = 'task_id';

    /**
     * {@inheritdoc}
     *
     * @param string|null                               $value
     * @param AbstractPlatform $platform
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?TaskId
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof TaskId) {
            return $value;
        }

        try {
            return new TaskId($value);
        } catch (\Exception $ex) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param TaskId|null $value
     * @param AbstractPlatform $platform
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof TaskId) {
            return $value->__toString();
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}