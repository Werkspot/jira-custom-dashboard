<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Sprint;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\InvalidDateException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
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
     * @throws EntityNotFoundException
     */
    public function findActive(): Sprint
    {
        $today = new DateTimeImmutable('today');

        $sprintCollection = $queryBuilder = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Sprint::class, 's')
            ->where('s.startDate <= :today')
            ->andWhere('s.endDate >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();

        if (empty($sprintCollection)) {
            throw new EntityNotFoundException();
        }

        return $sprintCollection[0];
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
    public function getNextSprintNumber(): int
    {
        $maxNumber = $this->em->createQueryBuilder()
            ->select('MAX(s.number) + 1')
            ->from(Sprint::class, 's')
            ->getQuery()
            ->getSingleScalarResult();

        if (is_null($maxNumber)) {
            return 0;
        }

        return (int)$maxNumber;
    }
}
