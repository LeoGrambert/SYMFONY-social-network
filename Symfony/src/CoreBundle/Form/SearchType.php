<?php
//
//namespace CoreBundle\Form;
//
//
//use CoreBundle\Repository\SpeciesRepository;
//use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Form\FormBuilderInterface;
//use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
//use Symfony\Component\OptionsResolver\OptionsResolver;
//
//class SearchType extends AbstractType
//{
//
//
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//
//        $builder
//            ->add('bird', AutocompleteType::class, ['class' => 'CoreBundle:Species'])
//
//            ->add('save', SubmitType::class, array(
//                'label' => 'Soumettre'
//            ))
//        ;
//
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => 'CoreBundle\Entity\Observation',
//        ]);
//    }
//}