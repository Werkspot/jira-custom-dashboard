<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
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
                'required'   => true,
            ])
            ->add('value', NumberType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
