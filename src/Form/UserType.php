<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    //Creating the registration form
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nombre de usuario',
                'attr' => ['placeholder' => 'Ej. Brole']
            ])
           // ->add('roles')
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'attr' => ['placeholder' => 'Introduce tu contraseña']
            ])
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['placeholder' => 'Ej. Lola']
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Ej. hola1234@hotmail.com']
            ])
            ->add('Enviar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
