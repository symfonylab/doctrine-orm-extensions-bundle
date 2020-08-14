<?php

declare(strict_types=1);

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine\Type;

use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Create datetime_immutable_microseconds datatype to support microseconds.
 */
class DateTimeImmutableMicrosecondsType extends Type
{
    const TYPENAME = 'datetime_immutable_microseconds'; // modify to match your type name

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if (isset($fieldDeclaration['version']) && true == $fieldDeclaration['version']) {
            return 'TIMESTAMP';
        }

        return 'DATETIME(6)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof DateTimeInterface) {
            return $value;
        }

        $val = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $value);

        if (!$val) {
            $val = date_create_immutable($value);
        }

        if (!$val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'Y-m-d H:i:s.u');
        }

        return $val;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s.u');
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTimeImmutable']);
    }

    public function getName()
    {
        return self::TYPENAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
