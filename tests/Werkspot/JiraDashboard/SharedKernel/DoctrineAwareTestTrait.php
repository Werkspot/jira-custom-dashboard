<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineEntityManagerFactory;
use Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Fixture\DoctrineConfidenceFixtureLoader;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\Fixture\DoctrineSprintFixtureLoader;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\Fixture\DoctrineTeamFixtureLoader;

trait DoctrineAwareTestTrait
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function setUpEntityManager(): void
    {
        $entityPaths = [
            __DIR__ . '/../../../../src/Werkspot/JiraDashboard/SharedKernel/Infrastructure/Persistence/Doctrine/Team/Mapping' => 'Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team',
            __DIR__ . '/../../../../src/Werkspot/JiraDashboard/SharedKernel/Infrastructure/Persistence/Doctrine/Sprint/Mapping' => 'Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint',
            __DIR__ . '/../../../../src/Werkspot/JiraDashboard/ConfidenceWidget/Infrastructure/Persistence/Doctrine/Confidence/Mapping' => 'Werkspot\JiraDashboard\ConfidenceWidget\Domain',
        ];

        $dbParams = [
            'url'     => $_ENV['DATABASE_URL'],
            'charset'  => 'utf8',
        ];

        $doctrineEntityManagerFactory = new DoctrineEntityManagerFactory($entityPaths, $dbParams);

        $this->entityManager = $doctrineEntityManagerFactory->getEntityManager();
    }

    protected function fixturesLoader(): void
    {
        $loader = new Loader();
        $loader->addFixture(new DoctrineTeamFixtureLoader());
        $loader->addFixture(new DoctrineSprintFixtureLoader());
        $loader->addFixture(new DoctrineConfidenceFixtureLoader());

        $purger   = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}
