<?php

namespace Undf\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Undf\LocationDataBundle\Entity\City;

/**
 * Stage
 *
 * @ORM\Table(name="stage")
 * @ORM\Entity(repositoryClass="Undf\PlanBundle\Entity\StageRepository")
 */
class Stage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Plan $plan
     *
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="stages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     * })
     */
    private $plan;

     /**
     * @var Admirer
     *
     * @ORM\OneToOne(targetEntity="Undf\LocationDataBundle\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="Link", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"url" = "ASC"})
     */
    private $links;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_start", type="boolean")
     */
    private $isStart;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_end", type="boolean")
     */
    private $isEnd;

    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function __toString()
    {
        return $this->city->getName();
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
     * Set plan
     *
     * @param \Undf\PlanBundle\Entity\Plan $plan
     * @return \Undf\PlanBundle\Entity\Stage
     */
    public function setPlan(Plan $plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return Plan
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set city
     *
     * @param Undf\LocationDataBundle\Entity\City $city
     * @return Stage
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return Undf\LocationDataBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     * @param integer $position
     * @return \Undf\PlanBundle\Entity\Stage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Set links
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $links
     * @return Plan
     */
    public function setLinks($links)
    {
        foreach($this->links as $link) {
            if($links->contains($link)) {
                $links->removeElement($link);
            } else {
                $this->removeLinks($link);
            }
        }
        foreach($links as $link) {
            $this->addLinks($link);
        }

        return $this;
    }

    /**
     * Add a link
     *
     * @param \Undf\PlanBundle\Entity\Link $link
     * @return \Undf\PlanBundle\Entity\Plan
     */
    public function addLinks(Link $link)
    {
        $link->setStage($this);

        $this->links->add($link);

        return $this;
    }

    public function removeLinks(Link $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set is_start
     *
     * @param boolean $isStart
     * @return \Undf\PlanBundle\Entity\Plan
     */
    public function setIsStart($isStart)
    {
        $this->isStart = $isStart;

        return $this;
    }

    /**
     * Get is_start
     * @return boolean
     */
    public function getIsStart()
    {
        return $this->isStart;
    }

    /**
     * Set is_end
     *
     * @param boolean $isEnd
     * @return \Undf\PlanBundle\Entity\Stage
     */
    public function setIsEnd($isEnd)
    {
        $this->isEnd = $isEnd;

        return $this;
    }

    /**
     * Get is_end
     *
     * @return boolean
     */
    public function getIsEnd()
    {
        return $this->isEnd;
    }


}
