<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Application\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;

class SaveConfidenceCommandHandler
{
    /**
     * @var ConfidenceRepositoryInterface
     */
    private $confidenceRepository;

    public function __construct(ConfidenceRepositoryInterface $confidenceRepository)
    {
        $this->confidenceRepository = $confidenceRepository;
    }

    public function handle(SaveConfidenceCommand $command): void
    {
        $confidence = new Confidence(
            $command->date(),
            $command->value()
        );

        $this->confidenceRepository->upsert($confidence);
    }
}
