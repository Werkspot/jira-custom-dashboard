<?php
declare(strict_types=1);

namespace Werkspot\Infrastructure\Symfony\Controller\User;

use Werkspot\Domain\User\Register\RegisterUserCommand;
use Werkspot\Infrastructure\Symfony\Form\Type\User\RegisterUserType;
use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RegisterUserController extends AbstractController
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
     * RegisterUserController constructor.
     * @param CommandBus $commandBus
     * @param EmitterInterface $emitter
     */
    public function __construct(CommandBus $commandBus, EmitterInterface $emitter)
    {
        $this->commandBus = $commandBus;
        $this->emitter = $emitter;
    }

    /**
     * @Route("/", methods={"GET", "POST"}, name="home")
     * @Template("User/register-user.html.twig")
     */
    public function __invoke(Request $request)
    {
        $form = $this->createForm(RegisterUserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $registerUserCommand = new RegisterUserCommand($formData['email'], $formData['password']);
                $this->commandBus->handle($registerUserCommand);
            } catch (\Throwable $t) {
                return [
                    'form' => $form->createView(),
                    'error' => $t->getMessage(),
                ];
            }

            return $this->redirect($this->generateUrl('show_message', [
                'message' => 'New user registered',
            ]));
        }

        return [
            'form' => $form->createView(),
            'error' => '',
        ];
    }
}
