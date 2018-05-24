<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Application\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceWidget;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;

class SaveConfidenceCommandHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    /**
     * @var ConfidenceRepositoryInterface
     */
    private $confidenceRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository, ConfidenceRepositoryInterface $confidenceRepository)
    {
        $this->confidenceRepository = $confidenceRepository;
        $this->sprintRepository = $sprintRepository;
    }

    public function handle(SaveConfidenceCommand $command): void
    {
        $value = $command->value(); // TODO borrar este apaño temporal

        $confidence = new Confidence(
            $command->date(),
            // TODO arreglar esto
            ConfidenceValueEnum::$value()
        );

        $confidenceWidget = new ConfidenceWidget($this->sprintRepository, $this->confidenceRepository);
        $confidenceWidget->saveConfidence($confidence);
    }
}
