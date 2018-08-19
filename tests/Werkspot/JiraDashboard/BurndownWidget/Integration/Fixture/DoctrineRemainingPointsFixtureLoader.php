<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\BurndownWidget\Integration\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\Fixture\DoctrineSprintFixtureLoader;

class DoctrineRemainingPointsFixtureLoader extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $sprint = $this->getReference(DoctrineSprintFixtureLoader::SPRINT_NAME);

        array_map(function (RemainingPoints $remainingPoints) use ($manager) {
            $manager->persist($remainingPoints);
        }, [

            new RemainingPoints($sprint, new \DateTimeImmutable('today - 10 days'), PositiveNumber::create(30)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 9 days'), PositiveNumber::create(25)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 8 days'), PositiveNumber::create(24)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 7 days'), PositiveNumber::create(19)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 6 days'), PositiveNumber::create(10)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 5 days'), PositiveNumber::create(9)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 4 days'), PositiveNumber::create(9)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 3 days'), PositiveNumber::create(8)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 2 days'), PositiveNumber::create(5)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 1 days'), PositiveNumber::create(3)),
        ]);

        $manager->flush();
    }
}
