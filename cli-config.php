<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineEntityManagerFactory;

require_once 'vendor/autoload.php';

$entityPaths = [
    __DIR__ . '/src/Werkspot/JiraDashboard/SharedKernel/Infrastructure/Persistence/Doctrine/Team/Mapping' => 'Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team',
    __DIR__ . '/src/Werkspot/JiraDashboard/SharedKernel/Infrastructure/Persistence/Doctrine/Sprint/Mapping' => 'Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint',
    __DIR__ . '/src/Werkspot/JiraDashboard/ConfidenceWidget/Infrastructure/Persistence/Doctrine/Confidence/Mapping' => 'Werkspot\JiraDashboard\ConfidenceWidget\Domain',
];

$dbParams = [
    'host' => 'mysql',
    'driver' => 'pdo_mysql',
    'dbname' => 'jiraboard',
    'user' => 'jiraboard',
    'password' => 'jiraboard',
    'charset' => 'utf8',
];

$doctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
    $entityPaths,
    $dbParams
);
$entityManager = $doctrineEntityManagerFactory->getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
