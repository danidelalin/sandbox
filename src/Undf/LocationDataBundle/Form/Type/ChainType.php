<?php

// src/Acme/TaskBundle/Form/Type/TaskType.php

namespace Undf\LocationDataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;
use Undf\LocationDataBundle\Entity\Country;
use Undf\LocationDataBundle\Entity\State;
use Undf\LocationDataBundle\Entity\City;
use Undf\LocationDataBundle\Entity\Address;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\Util\PropertyPath;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\Options;

class ChainType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data_class = $options['data_class'];
        $chain_data = $options['chain_data'];
        $factory = $builder->getFormFactory();

        $refreshLink = function($form, $field_name, $field_data, $previous_links) use ($factory) {
                    //Initialize mandatory options
                    $options = array(
                        'class' => $field_data['class'],
                        'property' => 'name',
                        'empty_value' => array_key_exists('empty_value', $field_data) ? $field_data['empty_value'] : false,
                    );

                    if (array_key_exists('collection_path', $field_data)) {
                        $path_to_collection = $field_data['collection_path'];
                        unset($field_data['collection_path']);
                    } else {
                        $path_to_collection = $field_name;
                    }

                    $unique_index = uniqid($path_to_collection);

                    //Initialize mandatory Html attrs ('chain-index' and 'onchange' event)
                    $options['attr'] = array_key_exists('attr', $field_data) ? $field_data['attr'] : array();
                    $options['attr']['chain-index'] = $path_to_collection;
                    $options['attr']['chain-elem-id'] = $unique_index;
                    if (array_key_exists('route', $field_data)) {
                        $route = $field_data['route'];
                        $options['attr']['onchange'] = "loadOptions($('[chain-elem-id=$unique_index]'), '$route', '$field_name' );";
                        unset($field_data['route']);
                    }

                    //Add any other option passed to the builder
                    $options = array_merge($field_data, $options);

                    if ($previous_links) {
                        //Make sure that chained selectors are empty before selection is done
                        $options['choices'] = array();

                        //Load next selector with values corresponding to selection
                        for ($i = count($previous_links) - 1; $i > -1; $i--) {
                            $link_data = $previous_links[$i];
                            if ($link_data['id']) {
                                unset($options['choices']);
                                $options['query_builder'] = function(EntityRepository $er) use ($path_to_collection, $previous_links, $link_data) {
                                            $qb = $er->createQueryBuilder('u');
                                            $qb->where('u IN (SELECT v FROM ' . $link_data['class'] . ' w LEFT JOIN w.' . $path_to_collection . ' v WHERE w.id = :prevId)')
                                                    ->setParameter('prevId', $link_data['id']);
                                            return $qb;
                                        };
                                break;
                            }
                        }
                    }
                    $form->add($factory->createNamed($field_name, 'entity', null, $options));
                };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (DataEvent $event) use ($data_class, $refreshLink, $chain_data) {

                    $form = $event->getForm();
                    $data = $event->getData();

                    if ($data == null)
                        return;

                    if ($data instanceof $data_class) {
                        $previous_links = array();
                        foreach ($chain_data as $link_name => $link_data) {
                            $refreshLink($form, $link_name, $link_data, $previous_links);

                            $property_path = new PropertyPath($link_name);
                            $property = $property_path->getValue($data);
                            $property_id = $property ? $property->getId() : null;

                            $link_data['id'] = $property_id;
                            $previous_links[] = $link_data;
                        }
                    }
                });

        $builder->addEventListener(FormEvents::PRE_BIND, function (DataEvent $event) use ($refreshLink, $chain_data) {
                    $form = $event->getForm();
                    $data = $event->getData();

                    $previous_links = array();
                    foreach ($chain_data as $link_name => $link_data) {
                        $refreshLink($form, $link_name, $link_data, $previous_links);

                        if (array_key_exists($link_name, $data)) {
                            $link_data['id'] = $data[$link_name];
                            $previous_links[] = $link_data;
                        }
                    }
                });
    }

    public function getName()
    {
        return 'chain';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $chain = function (Options $options, $value) {
                    $chain_path = '';
                    $chain_data = $options['chain_data'];
                    foreach ($chain_data as $link_name => $link_data) {
                        $chain_path .= array_key_exists('collection_path', $link_data) ? $link_data['collection_path'] : $link_name;
                        $chain_path .= ',';
                    }
                    $default_value = array(
                        'chain-container' => trim($chain_path, ', '),
                    );
                    return array_merge($default_value, $value);
                };
        $resolver->setRequired(array(
            'data_class',
            'chain_data',
        ))->setNormalizers(array('attr' => $chain));
    }

}
