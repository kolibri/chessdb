<?php declare(strict_types = 1);

namespace AppBundle\Doctrine\Types;

use AppBundle\Domain\PgnDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PgnDateType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toString();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return PgnDate::fromString($value);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'TINYTEXT';
    }

    public function getName()
    {
        return 'pgn_date';
    }
}
