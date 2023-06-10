<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class FilterActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudad', ChoiceType::class, [
                'choices' => [
                    'Madrid' => 'Madrid',
                    'Sevilla' => 'Sevilla',
                    'Barcelona' => 'Barcelona',
                ],
                'label' => 'Ciudad',
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Fecha',
                'required' => true,
                'data' => new \DateTime(), // Establece la fecha por defecto como la actual
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d H:i:s'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configuraciones adicionales del formulario si las necesitas
        ]);
    }
}
