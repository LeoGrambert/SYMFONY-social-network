<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 02/07/17
 * Time: 16:54
 */

namespace CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array('attr' => array('placeholder' => 'Nom'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez remplir le champ nom")),
                )
            ))
            ->add('firstname', TextType::class, array('attr' => array('placeholder' => 'Prénom'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez remplir le champ prénom")),
                )
            ))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Mail'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez remplir le champ email")),
                    new Email(array("message" => "Votre adresse email n'a pas un format valide")),
                )
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Message',
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez inscrire un message")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}