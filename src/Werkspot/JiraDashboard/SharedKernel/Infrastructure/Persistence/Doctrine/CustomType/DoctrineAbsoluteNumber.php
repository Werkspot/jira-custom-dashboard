<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\CustomType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\AbsoluteNumber;

class DoctrineAbsoluteNumber extends GuidType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'AbsoluteNumber';
    }

    /**
     * @param AbsoluteNumber $value
     * @param AbstractPlatform $platform
     *
     * @return int
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->number();
    }

    /**
     * @param int $value
     * @param AbstractPlatform $platform
     *
     * @return AbsoluteNumber
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return AbsoluteNumber::create((int)$value);
    }
}
