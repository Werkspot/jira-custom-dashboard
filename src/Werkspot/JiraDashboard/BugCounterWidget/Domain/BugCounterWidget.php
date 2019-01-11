<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BugCounterWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

final class BugCounterWidget implements WidgetInterface
{
    /** @var BugCounterRepositoryInterface */
    private $bugCounterRepository;

    public function __construct(BugCounterRepositoryInterface $bugCounterRepository)
    {
        $this->bugCounterRepository = $bugCounterRepository;
    }

    /**
     * @return BugCounter[]
     */
    public function getBugsCreatedByTeam(Team $team): array
    {
        $bugCounterCollection = $this->bugCounterRepository->findBugCounterByTeam($team);

        return $bugCounterCollection;
    }

    public function addBugToSprint(Sprint $sprint): void
    {
        $bugCounter = $this->bugCounterRepository->findBugCounterBySprint($sprint);

        $bugCounterNewValue = $bugCounter->getBugsCreatedCounter()->number();
        $bugCounter->setBugsCreatedCounter(PositiveNumber::create(++$bugCounterNewValue));

        $this->bugCounterRepository->upsert($bugCounter);
    }

    public function render(): Response
    {
        return new Response('RemainingPoints Widget');
    }
}
