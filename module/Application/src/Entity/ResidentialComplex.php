<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ResidentialComplex
 *
 * @ORM\Table(name="residential_complex")
 * @ORM\Entity
 */
class ResidentialComplex
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="distance", type="string", length=10, nullable=false)
     */
    private $distance;

    /**
     * @var string
     *
     * @ORM\Column(name="commissioning", type="string", length=5, nullable=false)
     */
    private $commissioning;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=255, nullable=false)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="long_description", type="text", nullable=false)
     */
    private $longDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="square", type="string", length=50, nullable=false)
     */
    private $square;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=10, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=255, nullable=false)
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     */
    private $location;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean", nullable=true)
     */
    private $del;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Employee", mappedBy="complex")
     */
    private $employee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Housing", mappedBy="complex")
     */
    private $housing;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->housing = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return ResidentialComplex
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
     * Set address
     *
     * @param string $address
     *
     * @return ResidentialComplex
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set distance
     *
     * @param string $distance
     *
     * @return ResidentialComplex
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return string
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set commissioning
     *
     * @param string $commissioning
     *
     * @return ResidentialComplex
     */
    public function setCommissioning($commissioning)
    {
        $this->commissioning = $commissioning;

        return $this;
    }

    /**
     * Get commissioning
     *
     * @return string
     */
    public function getCommissioning()
    {
        return $this->commissioning;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return ResidentialComplex
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     *
     * @return ResidentialComplex
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    /**
     * Get longDescription
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set square
     *
     * @param string $square
     *
     * @return ResidentialComplex
     */
    public function setSquare($square)
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Get square
     *
     * @return string
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return ResidentialComplex
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set transport
     *
     * @param string $transport
     *
     * @return ResidentialComplex
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return ResidentialComplex
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Add employee
     *
     * @param \Application\Entity\Employee $employee
     *
     * @return ResidentialComplex
     */
    public function addEmployee(\Application\Entity\Employee $employee)
    {
        $this->employee[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param \Application\Entity\Employee $employee
     */
    public function removeEmployee(\Application\Entity\Employee $employee)
    {
        $this->employee->removeElement($employee);
    }

    /**
     * Get employee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param $del
     * @return $this
     */
    public function setDel($del)
    {
        $this->del = $del;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDel()
    {
        return $this->del;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     * @param ArrayCollection $housing
     * @return $this
     */
    public function setHousing(ArrayCollection $housing)
    {
        $this->housing = $housing;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getObjects()
    {
        $objects = new ArrayCollection();
        $housings = $this->housing;
        /** @var Housing $housing */
        foreach ($housings as $housing)
        {
            $sections = $housing->getSections();
            /** @var Section $section */
            foreach ($sections as $section) {
                $objs = $section->getObjects();
                foreach ($objs as $obj) {
                    $objects->add($obj);
                }
            }
        }
        return $objects;
    }
}
