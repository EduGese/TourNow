<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;



class EditAdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', null, [
                'label' => 'Nombre de usuario',
            ])
            ->add('user_lastname', null, [
                'label' => 'Apellidos',
            ])
            ->add('tel', null, [
                'label' => 'Teléfono',
            ])
            ->add('company_name', null, [
                'label' => 'Nombre de la empresa',
            ])
            ->add('company_website', null, [
                'label' => 'Website de la empresa',
            ])
            ->add('dni', null, [
                'label' => 'DNI',
            ])
        
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'ejemplo@mail.com',
                    'required' => true,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
