<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Team;

use Doctrine\ORM\EntityManagerInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

final class TeamRepositoryDoctrineAdapter implements TeamRepositoryInterface
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
    public function find(Id $id): Team
    {
        $team = $this->em->getRepository(Team::class)->find($id);

        if (is_null($team)) {
            throw  new EntityNotFoundException();
        }

        return $team;
    }

    /**
     * @return Team[]|null
     */
    public function findAll(): ?array
    {
        return $this->em->getRepository(Team::class)->findAll();
    }
}
