<?php

namespace Undf\PlanBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Geocoder\GeocoderInterface;
use Doctrine\ORM\EntityManager;

class StageAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('city', 'sonata_type_model')
                ->add('isStart', null, array(
                    'required' => false
                ))
                ->add('isEnd', null, array(
                    'required' => false
                ))
                ->add('position', null, array(
                    'help_inline' => 'sdfsdf'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->add('plan')
                ->add('city')
                ->add('isStart')
                ->add('isEnd')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
                ->add('plan')
                ->add('city')
        ;
    }

}
