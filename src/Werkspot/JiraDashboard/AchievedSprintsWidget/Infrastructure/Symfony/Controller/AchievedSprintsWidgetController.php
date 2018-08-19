<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Infrastructure\Symfony\Controller;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\GetAchievedSprintsQuery;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\SetSprintAsAchievedCommand;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Infrastructure\Symfony\Form\Type\SetActiveSprintResultType;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SprintWidget\Domain\GetActiveSprintByTeamQuery;

class AchievedSprintsWidgetController extends AbstractController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @param CommandBus $commandBus
     * @param EmitterInterface $emitter
     */
    public function __construct(CommandBus $commandBus, EmitterInterface $emitter)
    {
        $this->commandBus = $commandBus;
        $this->emitter = $emitter;
    }

    /**
     * @Route("/achieved-sprints", methods={"GET", "POST"}, name="achieved_sprints")
     * @Template("AchievedSprintsWidget/achieved-sprints-widget.html.twig")
     */
    public function __invoke(Request $request)
    {
        $achievedSprints = null;

        try {
            $getAchievedSprintsQuery = new GetAchievedSprintsQuery();
            $achievedSprints = $this->commandBus->handle($getAchievedSprintsQuery);
        } catch (EntityNotFoundException $e) {
        }

        $activeSprint = null;

        try {
            $getActiveSprintQuery = new GetActiveSprintByTeamQuery();
            $activeSprint = $this->commandBus->handle($getActiveSprintQuery);
        } catch (EntityNotFoundException $e) {
        }

        $form = $this->createForm(SetActiveSprintResultType::class, null, [
            'action' => $this->generateUrl('achieved_sprints'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $setActiveSprintAsAchievedCommand = new SetSprintAsAchievedCommand($activeSprint->getId(), $formData['achieved']);
                $this->commandBus->handle($setActiveSprintAsAchievedCommand);

                $this->addFlash(
                    'result',
                    'Active Sprint updated correctly!'
                );

                $getAchievedSprintsQuery = new GetAchievedSprintsQuery();
                $achievedSprints = $this->commandBus->handle($getAchievedSprintsQuery);

                return $this->redirectToRoute('homepage', ['achievedSprints' => $achievedSprints]);
            } catch (\Throwable $t) {
                return [
                    'achievedSprints' => count($achievedSprints),
                    'activeSprint' => $activeSprint,
                    'form' => $form->createView(),
                    'error' => $t->getMessage(),
                ];
            }
        }

        return [
            'achievedSprints' => count($achievedSprints),
            'activeSprint' => $activeSprint,
            'form' => $form->createView(),
            'error' => '',
        ];
    }
}
