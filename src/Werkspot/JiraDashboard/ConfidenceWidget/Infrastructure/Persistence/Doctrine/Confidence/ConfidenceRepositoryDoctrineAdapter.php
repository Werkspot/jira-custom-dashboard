<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Persistence\Doctrine\Confidence;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
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
     * @throws EntityNotFoundException
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

        if (empty($confidenceCollection)) {
            throw new EntityNotFoundException();
        }

        return $confidenceCollection;
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
}
