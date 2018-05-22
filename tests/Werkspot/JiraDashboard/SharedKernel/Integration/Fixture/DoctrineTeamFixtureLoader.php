<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Integration\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;

class DoctrineTeamFixtureLoader extends AbstractFixture
{
    public const TEAM_NAME = 'Team name';

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $team = new Team(
            Id::create(),
            ShortText::create('Team name')
        );

        $manager->persist($team);
        $manager->flush();

        $this->addReference(self::TEAM_NAME, $team);
    }
}
