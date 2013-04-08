<?php

namespace Undf\LocationDataBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Geocoder\GeocoderInterface;
use Doctrine\ORM\EntityManager;

class CityAdmin extends Admin
{

    private $geocoder;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function setGeocoder(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name')
                ->add('image', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'provider' => 'sonata.media.provider.image',
                        'context' => 'cities'
                    ))
                )
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('name')
                ->add('latitude')
                ->add('longitude')
                ->add('enabled', null, array('editable' => true))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
                ->add('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $this->preUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $result = $this->geocoder
                ->using('google_maps')
                ->geocode($object->getName());
        $object
                ->setLatitude($result->getLatitude())
                ->setLongitude($result->getLongitude())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($object)
    {
        $this->postUpdate($object);
    }

    public function postUpdate($object)
    {
        $this->em->getConfiguration()->getQueryCacheImpl()->delete('globals_cities');
    }

}
