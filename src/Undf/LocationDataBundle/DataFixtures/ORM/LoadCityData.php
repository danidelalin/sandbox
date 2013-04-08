<?php

namespace Undf\LocationDataBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Undf\LocationDataBundle\Entity\City;
use Undf\LocationDataBundle\Entity\Province;
use Undf\LocationDataBundle\Entity\Country;

class LoadCityData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{

    private $container;

    function getOrder()
    {
        return 3;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $city = new City;
        $city->setName('Lalin');
        $city->setLatitude(43);
        $city->setLongitude(-8);

        $province = new Province;
        $province->setName('Pontevedra');
        $province->addCity($city);

        $country = new Country;
        $country->setName('España');
        $country->setCode('es');
        $country->addProvince($province);

        $city = new City;
        $city->setName('Barcelona');
        $city->setLatitude(45);
        $city->setLongitude(20);

        $province = new Province;
        $province->setName('Barcelona');
        $province->addCity($city);

        $city = new City;
        $city->setName('Badalona');
        $province->addCity($city);
        $city->setLatitude(45);
        $city->setLongitude(21);

        $country->addProvince($province);

        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush();
    }

    public function getEntityManager()
    {
        return $this->container->get('doctrine')->getEntityManager();
    }

}