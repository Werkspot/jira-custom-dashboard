<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\Event\EventAbstract;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

class SprintCreatedEvent extends EventAbstract
{
    /**
     * @var Sprint
     */
    private $sprint;

    public function __construct(string $id, Sprint $sprint)
    {
        parent::__construct($id);

        $this->sprint = $sprint;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Sprint.Created.Event';
    }

    public function getSprint(): Sprint
    {
        return $this->sprint;
    }
}
