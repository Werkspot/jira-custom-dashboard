<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\CustomType\DoctrinePositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\CustomType\DoctrineId;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\CustomType\DoctrineShortText;

final class DoctrineEntityManagerFactory
{
    /** @var array */
    private $entityPaths = [];

    /** @var array */
    private $connectionParams = [];

    /**
     * @throws DBALException
     */
    public function __construct(array $entityPaths, array $connectionParams)
    {
        $this->entityPaths      = $entityPaths;
        $this->connectionParams = $connectionParams;

        if (!Type::hasType('Id')) {
            Type::addType('Id', DoctrineId::class);
        }

        if (!Type::hasType('ShortText')) {
            Type::addType('ShortText', DoctrineShortText::class);
        }

        if (!Type::hasType('PositiveNumber')) {
            Type::addType('PositiveNumber', DoctrinePositiveNumber::class);
        }
    }

    /**
     * @throws ORMException
     */
    public function getEntityManager(): EntityManager
    {
        $isDevMode = true;
        $proxyDir  = null;
        $cache     = new ArrayCache();

        $config = Setup::createXMLMetadataConfiguration(
            $this->entityPaths,
            $isDevMode,
            $proxyDir,
            $cache
        );

        $driver = new \Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver($this->entityPaths);
        $config->setMetadataDriverImpl($driver);

        $entityManager = EntityManager::create($this->connectionParams, $config);

        return $entityManager;
    }
}
