<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Infrastructure\Symfony\Controller;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SprintWidget\Domain\AddNewSprintCommand;
use Werkspot\JiraDashboard\SprintWidget\Domain\GetActiveSprintByTeamQuery;
use Werkspot\JiraDashboard\SprintWidget\Infrastructure\Symfony\Form\Type\AddSprintType;

class SprintWidgetController extends AbstractController
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
     * @Route("/sprint", methods={"GET", "POST"}, name="sprint")
     * @Template("SprintWidget/sprint-widget.html.twig")
     */
    public function __invoke(Request $request)
    {
        $getActiveSprintQuery = new GetActiveSprintByTeamQuery();

        $activeSprint = null;

        try {
            $activeSprint = $this->commandBus->handle($getActiveSprintQuery);
        } catch (EntityNotFoundException $e) {
        }

        $form = $this->createForm(AddSprintType::class,  null, [
            'action' => $this->generateUrl('sprint'),

        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $addNewSprintCommand = new AddNewSprintCommand($formData['startDate'], $formData['endDate']);
                $this->commandBus->handle($addNewSprintCommand);

                $this->addFlash(
                    'sprint',
                    'Sprint added correctly!'
                );

                return $this->redirectToRoute('homepage');
            } catch (\Throwable $t) {
                return [
                    'activeSprint' => $activeSprint,
                    'form' => $form->createView(),
                    'error' => $t->getMessage(),
                ];
            }
        }

        return [
            'activeSprint' => $activeSprint,
            'form' => $form->createView(),
            'error' => '',
        ];
    }
}
