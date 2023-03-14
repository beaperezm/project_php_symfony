<?php

namespace App\Form;

use App\Entity\Athlete;
use App\Entity\TitlesWon;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class AthleteType extends AbstractType
{

    //Creando el formulario para añadir atletas

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['placeholder' => 'Ej. Rafa Nadal']
            ])

            ->add('description', TextType::class, [
                'label' => 'Descripción'
            ])
           
            ->add('imagenAtleta', DropzoneType::class, [
                'mapped' => false
            ])

            ->add('sport', TextType::class, [
                'label' => 'Deporte',
                'attr' => ['placeholder' => 'Ej. Tenista']
            ])

            ->add('birthDate', TextType::class, [
                'label' => 'Fecha de Nacimiento',
                'attr' => ['placeholder' => 'Ej. 23 de junio de 1986']
            ])
            ->add('code', TextType::class, [
                'label' => 'Código'
            ])

            ->add('titlesName', EntityType::class, [
      
                'class' => TitlesWon::class,
                'choice_label' => 'titles',
               'multiple' => true,
               'expanded' => true,
               'label' => 'Títulos ganados',
               'query_builder' => function(EntityRepository $repository) {
                return $repository -> createQueryBuilder('u')
                ->orderBy('u.titles', 'ASC');
               }
            ])
        
            ->add('Enviar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Athlete::class
        ]);
    }
}
