<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 *
 * @ORM\Table(name="section", indexes={@ORM\Index(name="section_housing_idx", columns={"housing_id"})})
 * @ORM\Entity
 */
class Section
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="apartment_floor", type="integer", nullable=false)
     */
    private $apartmentFloor;

    /**
     * @var \Application\Entity\Housing
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Housing")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     * })
     */
    private $housing;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="RealtyObject", mappedBy="section")
     */
    private $objects;

    /**
     * Section constructor.
     */
    public function __construct()
    {
        $this->objects = new ArrayCollection();
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
     * Set apartmentFloor
     *
     * @param integer $apartmentFloor
     *
     * @return Section
     */
    public function setApartmentFloor($apartmentFloor)
    {
        $this->apartmentFloor = $apartmentFloor;

        return $this;
    }

    /**
     * Get apartmentFloor
     *
     * @return integer
     */
    public function getApartmentFloor()
    {
        return $this->apartmentFloor;
    }

    /**
     * Set housing
     *
     * @param \Application\Entity\Housing $housing
     *
     * @return Section
     */
    public function setHousing(\Application\Entity\Housing $housing = null)
    {
        $this->housing = $housing;

        return $this;
    }

    /**
     * Get housing
     *
     * @return \Application\Entity\Housing
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     * @return ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param ArrayCollection $objects
     * @return $this
     */
    public function setObjects(ArrayCollection $objects)
    {
        $this->objects = $objects;
        return $this;
    }
}
