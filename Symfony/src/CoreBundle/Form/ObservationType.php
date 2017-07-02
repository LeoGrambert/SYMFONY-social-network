<?php

namespace CoreBundle\Form;


use CoreBundle\Repository\SpeciesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

            ->add('bird', AutocompleteType::class, ['class' => 'CoreBundle:Species'])

           /*->add('bird', EntityType::class, array(
               'class'        => 'CoreBundle:Species',
               'choice_label' => 'nomVern',
               'multiple'     => false,
               'query_builder' => function(SpeciesRepository $repository) {
                   return $repository->createQueryBuilder('s')
                       ->orderBy('s.nomVern', 'ASC');
               }
           ))*/

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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CoreBundle\Entity\Observation',
        ]);
    }
}