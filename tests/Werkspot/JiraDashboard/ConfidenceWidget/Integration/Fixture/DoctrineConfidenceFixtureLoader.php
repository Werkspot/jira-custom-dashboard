<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Fixture;

use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;

class DoctrineConfidenceFixtureLoader extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        array_map(function (Confidence $confidence) use ($manager) {
            $manager->persist($confidence);
        }, [
            new Confidence(new DateTimeImmutable('today - 4 days'), ConfidenceValueEnum::five()),
            new Confidence(new DateTimeImmutable('today - 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today - 2 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 1 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 0 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 1 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 2 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 4 days'), ConfidenceValueEnum::five()),
        ]);

        $manager->flush();
    }
}
