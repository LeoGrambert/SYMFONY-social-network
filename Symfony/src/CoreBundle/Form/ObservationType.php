<?php

namespace CoreBundle\Form;


use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',  DateTimeType::class,
                array(
                    'label'=> 'Date d\'observation',
                    'model_timezone' => 'Europe/Paris',
                    'data' => new \DateTime())
            )

            ->add('bird', AutocompleteType::class, ['class' => 'CoreBundle:Species'], array(
                'label' => 'Espèce - Nom commun',
                'required' => false
            ))

            ->add('description', TextareaType::class, array(
                'label' => 'Commentaire',
                'required' => false
            ))

            ->add('image', FileType::class, array(
                'label' => 'Image',
                'required' => false
            ))

            ->add("latitude", IntegerType::class, array(
                'label' => 'Latitude'
            ))
            ->add("longitude", IntegerType::class, array(
                'label' => 'Longitude'
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'Soumettre'
            ))
        ;
    }
}