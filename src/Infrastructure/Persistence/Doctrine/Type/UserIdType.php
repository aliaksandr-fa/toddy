<?php declare(strict_types=1);

namespace Toddy\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Ramsey\Uuid\Doctrine\UuidType;
use Toddy\Domain\Task\TaskId;
use Toddy\Domain\User\UserId;


/**
 * Class UserIdType
 * @package Toddy\Infrastructure\Persistence\Doctrine\Type
 * @author Faley Aliaksandr
 */
class UserIdType extends UuidType
{
    const NAME = 'user_id';

    /**
     * {@inheritdoc}
     *
     * @param string|null                               $value
     * @param AbstractPlatform $platform
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserId
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof UserId) {
            return $value;
        }

        try {
            return new UserId($value);
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

        if ($value instanceof UserId) {
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