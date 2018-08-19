<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\Fixture\DoctrineSprintFixtureLoader;

class DoctrineConfidenceFixtureLoader extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $sprint = $this->getReference(DoctrineSprintFixtureLoader::SPRINT_NAME);

        array_map(function (Confidence $confidence) use ($manager) {
            $manager->persist($confidence);
        }, [
            new Confidence(new \DateTimeImmutable('today - 4 days'), ConfidenceValueEnum::five(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 3 days'), ConfidenceValueEnum::four(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 2 days'), ConfidenceValueEnum::three(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 1 days'), ConfidenceValueEnum::three(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 0 days'), ConfidenceValueEnum::two(), $sprint),
        ]);

        $manager->flush();
    }
}
