<?php

namespace Undf\LocationDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation\Exclude;

/**
 * Undf\DataLocationBundle\Entity\Country
 *
 * @ORM\Table(name= "country")
 * @ORM\Entity(repositoryClass="Undf\LocationDataBundle\Entity\CountryRepository")
 */
class Country {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=4)
     */
    protected $code;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="City", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinTable(name="country_has_cities",
     *   joinColumns={
     *     @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="city_id", referencedColumnName="id", unique=true)
     *   }
     * )
     */
    protected $cities;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Province", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinTable(name="country_has_provinces",
     *   joinColumns={
     *     @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="province_id", referencedColumnName="id", unique=true)
     *   }
     * )
     */
    protected $provinces;

    public function __contruct() {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->provinces = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getId() {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Country
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Country
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
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
     * @return Country $this
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

    public function removeCity(City $city) {
        $this->cities->removeElement($city);
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProvinces() {
        return $this->provinces;
    }

    /**
     * Set Provinces
     *
     * @param \Doctrine\Common\Collections\ArrayCollection
     * @return Country $this
     */
    public function setProvinces($provinces) {
        $this->provinces = $provinces;

        return $this;
    }

    /**
     * Add a province to the list of provinces
     * @param Province $province
     */
    public function addProvince(Province $province) {
        $this->provinces[] = $province;
    }

    public function removeProvince(Province $province) {
        $this->provinces->removeElement($province);
    }

}
