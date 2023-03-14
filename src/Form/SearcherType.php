<?php

namespace App\Form;

use App\Entity\Athlete;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearcherType extends AbstractType
{

    //Creando el formulario de búsqueda por nombre

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('atleta', EntityType::class, [
                'class' => Athlete::class,
                'choice_label' => 'name',
                'placeholder' => 'Buscar nombre',
                'autocomplete' => true,
                'attr' => [
                    'onChange' => 'submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
