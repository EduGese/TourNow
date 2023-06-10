<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Positive;



class CreateActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('activity_name', TextType::class, [
                'label' => 'Nombre de la actividad',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => true,
            ])
            ->add('tickets', NumberType::class, [
                'label' => 'Entradas',
                'required' => true,
                'constraints' => [
                    new Positive([
                        'message' => 'El número de entradas debe ser un valor positivo.',
                    ]),
                ],
            ])
            ->add('start_ubication', TextType::class, [
                'label' => 'Ubicacion inicial',
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ],
                
               
            ])
            ->add('end_ubication', TextType::class, [
                'label' => 'Ubicacion final',
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ],
                
            ])
            ->add('start_coord', TextType::class, [
                'label' => 'Coordinadas iniciales',
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ],
               
            ])
            ->add('end_coord', TextType::class, [
                'label' => 'Coordinadas finales',
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ],
                
            ])
            ->add('price', NumberType::class, [
                'label' => 'Precio/persona',
                'required' => true,
                'constraints' => [
                    new Positive([
                        'message' => 'El precio debe ser un valor positivo.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Imagen (20 mb max)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'maxSizeMessage' => 'El archivo es demasiado grande. El tamaño máximo permitido es de 20MB.',
                    ]),
                ],
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Fecha',
                'required' => true,
                'data' => new \DateTime(), // Establece la fecha por defecto como la actual
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d H:i:s'),
                ],
            ])
            ->add('city', ChoiceType::class, [
                'label' => 'Ciudad',
                'required' => true,
                'choices' => [
                    'Madrid' => 'Madrid',
                    'Barcelona' => 'Barcelona',
                    'Sevilla' => 'Sevilla',
                ],
            ])
            ->add('company_name', TextType::class, [
                'label' => 'Nombre de tu empresa u organización',
                'required' => true,
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('company_website', TextType::class, [
                'label' => 'Página web',
                'required' => true,
                'attr' => [
                    'readonly' => true,
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^.+\.(com|es|net)$/i',
                        'message' => 'La página web debe terminar en .com, .es o .net',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
