<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Infrastructure\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AddSprintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'input' => 'datetime_immutable',
                'required' => true,
            ])
            ->add('endDate', DateType::class, [
                'input' => 'datetime_immutable',
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}
