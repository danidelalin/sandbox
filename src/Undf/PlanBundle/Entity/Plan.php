<?php

namespace Undf\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plan
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="Undf\PlanBundle\Entity\PlanRepository")
 */
class Plan
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="distance", type="float", nullable=true)
     */
    private $distance;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float", nullable=true)
     */
    private $duration;

    /**
     * @ORM\OneToMany(targetEntity="Stage", mappedBy="plan", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $stages;

    public function __construct()
    {
        $this->stages = new \Doctrine\Common\Collections\ArrayCollection;
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
     * @return Plan
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
     * Set distance
     *
     * @param float $distance
     * @return Plan
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set duration
     *
     * @param float $duration
     * @return Plan
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set stages
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $stages
     * @return Plan
     */
    public function setStages($stages)
    {
        foreach($this->stages as $stage) {
            if ($stages->contains($stage)) {
                $stages->removeElement($stage);
            } else {
                $this->removeStages($stage);
            }
        }
        foreach ($stages as $stage) {
            $this->addStages($stage);
        }

        return $this;
    }

    /**
     * Add a stage
     *
     * @param \Undf\PlanBundle\Entity\Stage $stage
     * @return \Undf\PlanBundle\Entity\Plan
     */
    public function addStages(Stage $stage)
    {
        $stage->setPlan($this);

        $this->stages->add($stage);

        return $this;
    }

    /**
     * Removes given stage
     *
     * @param \Undf\PlanBundle\Entity\Stage $stage
     * @return \Undf\PlanBundle\Entity\Plan
     */
    public function removeStages(Stage $stage)
    {
        $this->stages->removeElement($stage);

        return $this;
    }

    /**
     * Get stages
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getStages()
    {
        return $this->stages;
    }

}
