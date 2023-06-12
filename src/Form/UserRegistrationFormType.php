<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;


class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', null, [
                'label' => 'Nombre de usuario*',
                'required' => true,
            ])
            ->add('user_lastname', null, [
                'label' => 'Apellidos*',
                'required' => true,
            ])
            ->add('tel', null, [
                'label' => 'Telefono*',
                'attr' => [
                    'required' => true,
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Ingrese un número de teléfono válido.',
                    ]),
                ],
            ])
        
            ->add('email', EmailType::class, [
                'label' => 'Email*',
                'attr' => [
                    'placeholder' => 'ejemplo@mail.com',
                    'required' => true,
                ],
                'constraints' => [
                    new Email([
                        'message' => 'Ingrese una dirección de correo electrónico válida.',
                    ]),
                ],
            ])
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'La contraseña debe coincidir en los dos campos',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Contraseña*'],
                'second_options' => ['label' => 'Repite la contraseña*'],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduzca su contaseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Su contraseña debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
