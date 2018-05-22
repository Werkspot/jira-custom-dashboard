<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team;

use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;

class Team
{
    /** @var Id */
    private $id;

    /** @var ShortText */
    private $name;

    public function __construct(Id $teamId, ShortText $name)
    {
        $this->id = $teamId;
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }
}
