<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Symfony\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;
use Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Symfony\Form\Type\AddConfidenceType;

class ConfidenceWidgetController extends AbstractController
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
     * @Route("/confidence", methods={"GET", "POST"}, name="confidence")
     * @Template("ConfidenceWidget/confidence-widget.html.twig")
     */
    public function __invoke(Request $request)
    {
        // Confidence of the Active sprint
        $confidenceOfActiveSprint = $this->confidenceLineChart();

        $form = $this->createForm(AddConfidenceType::class, null, [
            'action' => $this->generateUrl('confidence'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $saveConfidenceCommand = new SaveConfidenceCommand($formData['date'], $formData['value']);
                $this->commandBus->handle($saveConfidenceCommand);

                $this->addFlash(
                    'confidence',
                    'Confidence added correctly!'
                );

                return $this->redirectToRoute('homepage');
            } catch (\Throwable $t) {
                return [
                    'confidenceActiveSprint' => $confidenceOfActiveSprint,
                    'form' => $form->createView(),
                    'error' => $t->getMessage(),
                ];
            }
        }

        return [
            'confidenceActiveSprint' => $confidenceOfActiveSprint,
            'form' => $form->createView(),
            'error' => '',
        ];
    }

    private function confidenceLineChart()
    {
        $line = new LineChart();
        $line->getData()->setArrayToDataTable(
            [
                [
                    ['label' => 'x', 'type' => 'number'],
                    ['label' => 'Confidence', 'type' => 'number'],
                ],
                [1, 1], // dia 1
                [2, 5], // dia 2
                [3, 2], // dia 3
                [4, 5], // dia 4
                [5, 3], // dia 5
                [6, 5], // dia 6
                [7, 4], // dia 7
                [8, 5], // dia 8
            ]
        );

        $line->getOptions()->setLineWidth(2);
        $line->getOptions()->setPointSize(5);
        $line->getOptions()->setWidth(300);
        $line->getOptions()->setHeight(120);

        $line->getOptions()->getHAxis()->setFormat('0');
        $line->getOptions()->getVAxis()->setFormat('0');
        $line->getOptions()->getVAxis()->setMinValue(1);
        $line->getOptions()->getVAxis()->setBaseline(1);

        return $line;
    }
}
