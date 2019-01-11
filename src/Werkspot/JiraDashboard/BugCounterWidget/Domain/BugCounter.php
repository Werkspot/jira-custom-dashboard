<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BugCounterWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

class BugCounter
{
    /** @var Id */
    private $id;

    /** @var Sprint */
    private $sprint;

    /** @var PositiveNumber */
    private $bugsCreatedCounter;

    /** @var PositiveNumber */
    private $bugsSolvedCounter;

    /** @var PositiveNumber */
    private $totalBugsCounter;


    public function __construct(Sprint $sprint, PositiveNumber $bugsCreatedCounter, PositiveNumber $bugsSolvedCounter, PositiveNumber $totalBugsCounter)
    {
        $this->id = Id::create();
        $this->sprint = $sprint;
        $this->bugsCreatedCounter = $bugsCreatedCounter;
        $this->bugsSolvedCounter = $bugsSolvedCounter;
        $this->totalBugsCounter = $totalBugsCounter;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function setSprint(Sprint $sprint): self
    {
        $this->sprint = $sprint;

        return $this;
    }

    public function getSprint(): Sprint
    {
        return $this->sprint;
    }

    public function getBugsCreatedCounter(): PositiveNumber
    {
        return $this->bugsCreatedCounter;
    }

    public function setBugsCreatedCounter(PositiveNumber $bugsCreatedCounter): void
    {
        $this->bugsCreatedCounter = $bugsCreatedCounter;
    }

    public function getBugsSolvedCounter(): PositiveNumber
    {
        return $this->bugsSolvedCounter;
    }

    public function setBugsSolvedCounter(PositiveNumber $bugsSolvedCounter): void
    {
        $this->bugsSolvedCounter = $bugsSolvedCounter;
    }

    public function getTotalBugsCounter(): PositiveNumber
    {
        return $this->totalBugsCounter;
    }

    public function setTotalBugsCounter(PositiveNumber $totalBugsCounter): void
    {
        $this->totalBugsCounter = $totalBugsCounter;
    }
}
