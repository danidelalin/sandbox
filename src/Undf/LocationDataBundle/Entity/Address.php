<?php

namespace Undf\LocationDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Undf\StoreBundle\Entity\Address
 *
 * @ORM\Table(name= "address")
 * @ORM\Entity
 */
class Address
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
     * @var string $street
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    protected $street;

    /**
     * @var integer $number
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    protected $number;

    /**
     * @var string $door
     *
     * @ORM\Column(name="door", type="string", length=10, nullable=true)
     */
    protected $door;

    /**
     * @var string $postal_code
     *
     * @ORM\Column(name="postal_code", type="string", length=10, nullable=true)
     */
    protected $postal_code;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=true)
     * })
     */
    protected $city;

    /**
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="Province")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="province_id", referencedColumnName="id", nullable=true)
     * })
     */
    protected $province;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=true)
     * })
     */
    protected $country;

    public function __toString() {
        return $this->street.' '.$this->number.' '.$this->door.PHP_EOL.$this->postal_code.PHP_EOL.$this->country.PHP_EOL.$this->province.PHP_EOL.$this->city;
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
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Address
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set door
     *
     * @param string $door
     * @return Address
     */
    public function setDoor($door)
    {
        $this->door = $door;

        return $this;
    }

    /**
     * Get door
     *
     * @return string
     */
    public function getDoor()
    {
        return $this->door;
    }

    /**
     * Set postal_code
     *
     * @param string $postalCode
     * @return Student
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;

        return $this;
    }

    /**
     * Get postal_code
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set city
     *
     * @param City $city
     * @return Address
     */
    public function setCity(City $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set province
     *
     * @param Province $province
     * @return Address
     */
    public function setProvince(Province $province = null)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return Province
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return Address
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
