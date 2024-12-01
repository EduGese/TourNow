<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FilterActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudad', ChoiceType::class, [
                'choices' => [
                    'Elige ciudad' => 'Elige ciudad',//Esto no se puede cambiar
                    'Madrid' => 'Madrid',
                    'Sevilla' => 'Sevilla',
                    'Barcelona' => 'Barcelona',
                ],
                'label' => 'Ciudad',
            ])
            ->add('date', DateType::class, [
                'label' => 'Fecha',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            
        ]);
    }
}
