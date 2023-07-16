<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbCouverts', IntegerType::class, [
                'label' => 'Nombre de couverts',
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 1])
                ]
            ])
            ->add('date', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'choice',
                'hours' => range(12, 21),
                'minutes' => [0, 15, 30, 45],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(['value' => 'today'])
                ]
            ])
            ->add('allergies', TextareaType::class, [
                'required' => false,
                'label' => 'Mention des allergies (optionnel)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
