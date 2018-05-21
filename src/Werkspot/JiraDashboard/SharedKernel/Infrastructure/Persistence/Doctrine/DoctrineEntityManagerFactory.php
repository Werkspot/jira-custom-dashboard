<?php
declare(strict_types=1);

namespace Werkspot\SharedKernel\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Werkspot\Infrastructure\Persistence\Doctrine\CustomType\DoctrineEmail;
use Werkspot\Infrastructure\Persistence\Doctrine\CustomType\DoctrinePassword;
use Werkspot\Infrastructure\Persistence\Doctrine\CustomType\DoctrineUserId;

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

        if (!Type::hasType('UserId')) {
            Type::addType('UserId', DoctrineUserId::class);
        }

        if (!Type::hasType('Email')) {
            Type::addType('Email', DoctrineEmail::class);
        }

        if (!Type::hasType('Password')) {
            Type::addType('Password', DoctrinePassword::class);
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
