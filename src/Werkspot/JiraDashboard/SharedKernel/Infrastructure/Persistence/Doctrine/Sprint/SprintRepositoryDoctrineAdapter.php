<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Sprint;

use Doctrine\ORM\EntityManagerInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
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
        $today = new \DateTimeImmutable('today');

        $queryBuilder = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Sprint::class, 's')
            ->where('s.startDate <= :today')
            ->andWhere('s.endDate >= :today')
            ->setParameter('today', $today)
            ->getQuery();

        $sprintCollection = $queryBuilder->execute();

        if (empty($sprintCollection)) {
            throw new EntityNotFoundException();
        }

        return $sprintCollection[0];
    }
}
