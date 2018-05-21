<?php
declare(strict_types=1);

namespace Werkspot\Tests\SharedKernel;

use Doctrine\ORM\EntityManager;
use Werkspot\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineEntityManagerFactory;

trait DoctrineAwareTestTrait
{
    /** @var EntityManager */
    protected $entityManager;

    protected function setUpEntityManager(): void
    {
        $entityPaths = [
            __DIR__ . '/../../src/Werkspot/Infrastructure/Persistence/Doctrine/User/Mapping' => 'Werkspot\Domain\User',
        ];

        $dbParams = [
            'url'     => $_ENV['DATABASE_URL'],
            'charset'  => 'utf8',
        ];

        $doctrineEntityManagerFactory = new DoctrineEntityManagerFactory($entityPaths, $dbParams);

        $this->entityManager = $doctrineEntityManagerFactory->getEntityManager();
    }
}
