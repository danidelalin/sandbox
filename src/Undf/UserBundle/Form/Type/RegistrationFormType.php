<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Undf\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseRegistrationFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username', null, array(
                    'label' => 'form.username',
                    'translation_domain' => 'FOSUserBundle',
                    'widget_control_group_attr' => array('class' => 'control-group'),
                    'widget_controls_attr' => array('class' => 'controls'),
                ))
                ->add('email', 'email', array(
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'widget_control_group_attr' => array('class' => 'control-group'),
                    'widget_controls_attr' => array('class' => 'controls'),
                ))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                    'widget_control_group_attr' => array('class' => 'control-group'),
                    'widget_controls_attr' => array('class' => 'controls'),
                ))
        ;
    }

    public function getName()
    {
        return 'undf_user_registration';
    }

}
