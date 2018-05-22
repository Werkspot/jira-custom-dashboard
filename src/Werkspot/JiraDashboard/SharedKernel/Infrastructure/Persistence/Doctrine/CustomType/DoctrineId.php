<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\CustomType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class DoctrineId extends GuidType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Id';
    }

    /**
     * @param Id $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     *
     * @return Id
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Id::create($value);
    }
}
