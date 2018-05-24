<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AddConfidenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'input' => 'datetime_immutable',
                'required' => true,
            ])
            ->add('value', ChoiceType::class, [
                'choices' => [
                    1 => 'one',
                    2 => 'two',
                    3 => 'three',
                    4 => 'four',
                    5 => 'five',
                ]
            ])
//            ->add('value', NumberType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
