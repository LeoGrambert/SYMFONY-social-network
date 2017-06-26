<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 26/06/17
 * Time: 11:07
 */

namespace CoreBundle\Form;

use CoreBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', ChoiceType::class, [
             'choices' => [
                'Je suis un ornithologue professionnel' => 'ROLE_PRO'
            ],
            'expanded' => true,
            'multiple' => true
        ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}