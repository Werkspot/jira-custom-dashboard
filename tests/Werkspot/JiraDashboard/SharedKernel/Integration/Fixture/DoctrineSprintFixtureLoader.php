<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Integration\Fixture;

use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\AbsoluteNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;

class DoctrineSprintFixtureLoader extends AbstractFixture
{
    public const SPRINT_NAME = 'Sprint name';

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $sprint = new Sprint(
            Id::create(),
            ShortText::create('Sprint title'),
            $this->getReference(DoctrineTeamFixtureLoader::TEAM_NAME),
            new DateTimeImmutable('today -4 days'),
            new DateTimeImmutable('today +4 days'),
            AbsoluteNumber::create(1)
        );

        $manager->persist($sprint);
        $manager->flush();

        $this->addReference(self::SPRINT_NAME, $sprint);
    }
}
