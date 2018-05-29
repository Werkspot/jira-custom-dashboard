<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Infrastructure\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SetActiveSprintResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('achieved', CheckboxType::class, [])
            ->add('send', SubmitType::class)
        ;
    }
}
