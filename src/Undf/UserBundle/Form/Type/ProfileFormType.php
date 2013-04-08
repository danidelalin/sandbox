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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;

class ProfileFormType extends BaseProfileFormType
{

    public function getName()
    {
        return 'undf_user_profile';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'widget_control_group_attr' => array('class' => 'control-group'),
            'widget_controls_attr' => array('class' => 'controls'),
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
            'always_empty' => false
        ));
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('imageName', null, array(
                    'label' => 'form.imageName',
                    'translation_domain' => 'FOSUserBundle'
                ))
                ->add('image', null, array(
                    'label' => 'form.image',
                    'translation_domain' => 'FOSUserBundle'
                ))
                ->add('username', null, array(
                    'widget_control_group_attr' => array('class' => 'control-group'),
                    'widget_controls_attr' => array('class' => 'controls'),
                    'label' => 'form.username',
                    'translation_domain' => 'FOSUserBundle'))
                ->add('email', 'email', array(
                    'widget_control_group_attr' => array('class' => 'control-group'),
                    'widget_controls_attr' => array('class' => 'controls'),
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle'))
        ;
    }

}
