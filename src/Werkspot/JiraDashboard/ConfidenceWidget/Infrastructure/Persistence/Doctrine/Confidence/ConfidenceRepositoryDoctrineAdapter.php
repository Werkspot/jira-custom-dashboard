<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Persistence\Doctrine\Confidence;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

final class ConfidenceRepositoryDoctrineAdapter implements ConfidenceRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Confidence[]
     */
    public function findBySprint(Sprint $sprint): array
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Confidence::class, 'c')
            ->where('c.date >= :startDate')
            ->andWhere('c.date <= :endDate')
            ->setParameter('startDate', $sprint->getStartDate())
            ->setParameter('endDate', $sprint->getEndDate())
            ->orderBy('c.date', 'ASC')
            ->getQuery();

        $confidenceCollection = $queryBuilder->execute(null, Query::HYDRATE_ARRAY);

        $sprintPeriod = new \DatePeriod(
            new \DateTime($sprint->getStartDate()->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime($sprint->getEndDate()->format('Y-m-d'))
        );

        $confidenceArray = [];
        /** @var \DateTime $day */
        foreach ($sprintPeriod as $day) {
            $confidenceArray[] = $this->getConfidenceByDay($confidenceCollection, $day);
        }

        return $confidenceArray;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findByDate(DateTimeImmutable $date): Confidence
    {
        $confidenceByDate = $this->em->getRepository(Confidence::class)->findByDate($date);

        if (empty($confidenceByDate)) {
            throw new EntityNotFoundException();
        }

        return $confidenceByDate[0];
    }

    public function upsert(Confidence $confidence): void
    {
        try {
            $existingConfidence = $this->findByDate($confidence->getDate());
            $existingConfidence->setValue($confidence->getValue());
        } catch (EntityNotFoundException $e) {
        }

        $this->em->persist($confidence);
        $this->em->flush();
    }

    private function getConfidenceByDay(array $confidenceCollection, \DateTime $day): array
    {
        foreach ($confidenceCollection as $confidence) {
            if ($confidence['date']->format('Y-m-d') == $day->format('Y-m-d')) {
                return $confidence;
            }
        }

        return [
            'date' => new \DateTimeImmutable($day->format('Y-m-d')),
            'value' => ConfidenceValueEnum::zero()->value(),
        ];
    }
}
