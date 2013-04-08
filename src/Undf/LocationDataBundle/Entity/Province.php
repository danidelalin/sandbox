<?php

namespace Undf\LocationDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation\Exclude;

/**
 * Undf\DataLocationBundle\Entity\Province
 *
 * @ORM\Table(name= "province")
 * @ORM\Entity(repositoryClass="Undf\LocationDataBundle\Entity\ProvinceRepository")
 */
class Province
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    protected $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="City", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinTable(name="province_has_cities",
     *   joinColumns={
     *     @ORM\JoinColumn(name="province_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="city_id", referencedColumnName="id", unique=true)
     *   }
     * )
     */
    protected $cities;

    public function __contruct()
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Province
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCities() {
        return $this->cities;
    }

    /**
     * Set cities
     *
     * @param \Doctrine\Common\Collections\ArrayCollection
     * @return Province $this
     */
    public function setCities( $cities) {
        $this->cities = $cities;

        return $this;
    }

    /**
     * Add a city to the list of cities
     * @param City $city
     */
    public function addCity(City $city) {

        $this->cities[] = $city;
    }

    /**
     * Remove the given city from the list of cities
     * @param City $city
     */
    public function removeCity(City $city) {

        $this->cities->removeElement($city);
    }

}
