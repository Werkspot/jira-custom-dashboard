<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Infrastructure\Persistence\Doctrine\RemainingPoints;

use Doctrine\ORM\EntityManagerInterface;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

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

        $remainingPointsArray = [];
        /** @var \DateTime $day */
        foreach ($sprint->getPeriod() as $day) {
            $weekday = $day->format('N');
            if ($weekday !== '6' && $weekday !== '7') { // all except Saturday and Sunday
                $remainingPointsArray[] = $this->getRemainingPointsByDay($sprint, $remainingPointsCollection, $day);
            }
        }

        return $remainingPointsArray;
    }

    public function findByDate(\DateTimeImmutable $date): ?RemainingPoints
    {
        return $this->em->getRepository(RemainingPoints::class)->findOneByDate($date);
    }

    public function upsert(RemainingPoints $remainingPoints): void
    {
        $this->em->persist($remainingPoints);
        $this->em->flush();
    }

    /**
     * @param RemainingPoints[] $getRemainingPointsCollection
     * @throws \Exception
     */
    private function getRemainingPointsByDay(Sprint $sprint, array $getRemainingPointsCollection, \DateTime $day): RemainingPoints
    {
        foreach ($getRemainingPointsCollection as $remainingPoints) {
            if ($remainingPoints->getDate()->format('Y-m-d') == $day->format('Y-m-d')) {
                return $remainingPoints;
            }
        }

        return new RemainingPoints($sprint, \DateTimeImmutable::createFromMutable($day), PositiveNumber::create(0));
    }
}
