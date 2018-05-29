<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Symfony\Controller;

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
     * @Template("ConfidenceWidget/confidences.html.twig")
     */
    public function __invoke(Request $request)
    {
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
                    'form' => $form->createView(),
                    'error' => $t->getMessage(),
                ];
            }
        }

        return [
            'form' => $form->createView(),
            'error' => '',
        ];
    }
}
