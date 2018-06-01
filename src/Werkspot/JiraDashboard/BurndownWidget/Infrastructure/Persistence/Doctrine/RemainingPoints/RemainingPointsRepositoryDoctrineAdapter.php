<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Infrastructure\Persistence\Doctrine\RemainingPoints;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

final class RemainingPointsRepositoryDoctrineAdapter implements RemainingPointsRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * RemainingPoints[]
     */
    public function findBySprint(Sprint $sprint): array
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('r')
            ->from(RemainingPoints::class, 'r')
            ->join('r.sprint', 's')
            ->where('s.id = :sprintId')
            ->setParameter('sprintId', $sprint->getId()->id())
            ->orderBy('r.date', 'ASC')
            ->getQuery();

        $remainingPointsCollection = $queryBuilder->execute();

        return $remainingPointsCollection;
    }

    public function findByDate(DateTimeImmutable $date): ?RemainingPoints
    {
        return $this->em->getRepository(RemainingPoints::class)->findOneByDate($date);
    }

    public function upsert(RemainingPoints $remainingPoints): void
    {
        $this->em->persist($remainingPoints);
        $this->em->flush();
    }
}
