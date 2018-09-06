<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Sprint;

use Doctrine\ORM\EntityManagerInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

final class SprintRepositoryDoctrineAdapter implements SprintRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function find(Id $id): Sprint
    {
        $sprint = $this->em->getRepository(Sprint::class)->find($id);

        if (is_null($sprint)) {
            throw  new EntityNotFoundException();
        }

        return $sprint;
    }

    /**
     * @return Sprint[]|null
     */
    public function findAll(): ?array
    {
        return $this->em->getRepository(Sprint::class)->findAll();
    }

    /**
     * @return Sprint[]|null
     * @throws EntityNotFoundException
     */
    public function findAllByTeam(Id $teamId): ?array
    {
        $team = $this->getTeamById($teamId);

        return $this->em->getRepository(Sprint::class)->findByTeam($team);
    }

    /**
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findActiveByTeam(Id $teamId): Sprint
    {
        $team = $this->getTeamById($teamId);

        $today = new \DateTimeImmutable('today');

        $sprint = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Sprint::class, 's')
            ->where('s.startDate <= :today')
            ->andWhere('s.endDate >= :today')
            ->setParameter('today', $today)
            ->andWhere('s.team = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->getSingleResult();

        return $sprint;
    }

    public function findAllOrderByNumber(): ?array
    {
        // TODO: Implement findAllOrderByNumber() method.
    }

    public function upsert(Sprint $sprint): void
    {
        $this->em->persist($sprint->getTeam()); // TODO this is temp
        $this->em->persist($sprint);
        $this->em->flush();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNextSprintNumberByTeam(Team $team): int
    {
        $maxNumber = $this->em->createQueryBuilder()
            ->select('MAX(s.number) + 1')
            ->from(Sprint::class, 's')
            ->where('s.team = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->getSingleScalarResult();

        if (is_null($maxNumber)) {
            return 0;
        }

        return (int)$maxNumber;
    }

    /**
     * @return Sprint[]|null
     * @throws EntityNotFoundException
     */
    public function findAchievedByTeam(Id $teamId): ?array
    {
        $team = $this->getTeamById($teamId);

        return $this->em->createQueryBuilder()
            ->select('s')
            ->from(Sprint::class, 's')
            ->join( 's.team', 't')
            ->where('s.achieved = 1')
            ->andWhere('t.id = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllByTeamOrderByNumber(Id $teamId): ?array
    {
        // TODO: Implement findAllByTeamOrderByNumber() method.
    }

    /**
     * @throws EntityNotFoundException
     */
    private function getTeamById(Id $teamId): Team
    {
        if (!$team = $this->em->getRepository(Team::class)->find($teamId)) {
            throw new EntityNotFoundException("Team $teamId not found.");
        }

        return $team;
    }
}
