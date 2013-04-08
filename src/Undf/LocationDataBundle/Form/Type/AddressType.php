<?php

namespace Undf\LocationDataBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Undf\LocationDataBundle\Entity\Address;

class AddressType extends ChainType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = $options['required'];
        $translationDomain = $options['translation_domain'];

        $builder
                ->add('street', null, array(
                    'required' => $required,
                    'label' => 'form.address.label_street',
                    'translation_domain' => $translationDomain
                ))
                ->add('number', null, array(
                    'required' => $required,
                    'label' => 'form.address.label_number',
                    'translation_domain' => $translationDomain
                ))
                ->add('door', null, array(
                    'required' => false,
                    'label' => 'form.address.label_door',
                    'translation_domain' => $translationDomain
                ))
                ->add('postal_code', null, array(
                    'required' => $required,
                    'label' => 'form.address.label_postal_code',
                    'translation_domain' => $translationDomain
                ))
        ;
    }

    public function getParent()
    {
        return 'chain';
    }

    public function getName()
    {
        return 'address';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $address_chain_data = array(
            'country' => array(
                'label' => 'form.address.label_country',
                'class' => 'UndfLocationDataBundle:Country',
                'route' => 'undf_provinces',
                'empty_value' => '-- Selecciona un país --',
            ),
            'province' => array(
                'label' => 'form.address.label_province',
                'class' => 'UndfLocationDataBundle:Province',
                'collection_path' => 'provinces',
                'route' => 'undf_cities',
                'empty_value' => '-- Selecciona una provincia --',
            ),
            'city' => array(
                'label' => 'form.address.label_city',
                'class' => 'UndfLocationDataBundle:City',
                'collection_path' => 'cities',
                'empty_value' => '-- Selecciona una ciudad --',
            ),
        );
        $resolver->setDefaults(array(
            'data' => new Address,
            'data_class' => 'Undf\LocationDataBundle\Entity\Address',
            'chain_data' => $address_chain_data,
        ));
    }

}