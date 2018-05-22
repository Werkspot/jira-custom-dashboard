<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\CustomType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;

class DoctrineShortText extends GuidType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'ShortText';
    }

    /**
     * @param ShortText $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->title();
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     *
     * @return ShortText
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ShortText::create($value);
    }
}
