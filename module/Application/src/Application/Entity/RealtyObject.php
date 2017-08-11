<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RealtyObject
 *
 * @ORM\Table(name="realty_object", indexes={@ORM\Index(name="object_type_idx", columns={"realty_type_id"}), @ORM\Index(name="object_employee_idx", columns={"employee_id"}), @ORM\Index(name="object_section_idx", columns={"section_id"}), @ORM\Index(name="object_images_idx", columns={"plan"})})
 * @ORM\Entity
 */
class RealtyObject
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
     * @ORM\Column(name="crm_id", type="integer", nullable=false)
     */
    private $crmId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="square", type="string", length=255, nullable=true)
     */
    private $square;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="rooms", type="string", length=255, nullable=true)
     */
    private $rooms;

    /**
     * @var string
     *
     * @ORM\Column(name="point", type="string", length=255, nullable=true)
     */
    private $point;

    /**
     * @var string
     *
     * @ORM\Column(name="metro", type="string", length=255, nullable=true)
     */
    private $metro;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255, nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="levels", type="string", length=255, nullable=true)
     */
    private $levels;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publish", type="boolean", nullable=true)
     */
    private $publish;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    private $dateEdit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_exp", type="datetime", nullable=true)
     */
    private $dateExp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="renta", type="boolean", nullable=true)
     */
    private $renta;

    /**
     * @var boolean
     *
     * @ORM\Column(name="special", type="boolean", nullable=true)
     */
    private $special;

    /**
     * @var \Application\Entity\Employee
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     * })
     */
    private $employee;

    /**
     * @var \Application\Entity\ObjectImages
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\ObjectImages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan", referencedColumnName="id")
     * })
     */
    private $plan;

    /**
     * @var \Application\Entity\Section
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    private $section;

    /**
     * @var \Application\Entity\RealtyType
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RealtyType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="realty_type_id", referencedColumnName="id")
     * })
     */
    private $realtyType;



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
     * Set crmId
     *
     * @param integer $crmId
     *
     * @return RealtyObject
     */
    public function setCrmId($crmId)
    {
        $this->crmId = $crmId;

        return $this;
    }

    /**
     * Get crmId
     *
     * @return integer
     */
    public function getCrmId()
    {
        return $this->crmId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return RealtyObject
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
     * Set description
     *
     * @param string $description
     *
     * @return RealtyObject
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return RealtyObject
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
     * Set square
     *
     * @param string $square
     *
     * @return RealtyObject
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
     * @return RealtyObject
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
     * Set rooms
     *
     * @param string $rooms
     *
     * @return RealtyObject
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get rooms
     *
     * @return string
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set point
     *
     * @param string $point
     *
     * @return RealtyObject
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return string
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set metro
     *
     * @param string $metro
     *
     * @return RealtyObject
     */
    public function setMetro($metro)
    {
        $this->metro = $metro;

        return $this;
    }

    /**
     * Get metro
     *
     * @return string
     */
    public function getMetro()
    {
        return $this->metro;
    }

    /**
     * Set level
     *
     * @param string $level
     *
     * @return RealtyObject
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set levels
     *
     * @param string $levels
     *
     * @return RealtyObject
     */
    public function setLevels($levels)
    {
        $this->levels = $levels;

        return $this;
    }

    /**
     * Get levels
     *
     * @return string
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * Set publish
     *
     * @param boolean $publish
     *
     * @return RealtyObject
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish
     *
     * @return boolean
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return RealtyObject
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set dateEdit
     *
     * @param \DateTime $dateEdit
     *
     * @return RealtyObject
     */
    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;

        return $this;
    }

    /**
     * Get dateEdit
     *
     * @return \DateTime
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * Set dateExp
     *
     * @param \DateTime $dateExp
     *
     * @return RealtyObject
     */
    public function setDateExp($dateExp)
    {
        $this->dateExp = $dateExp;

        return $this;
    }

    /**
     * Get dateExp
     *
     * @return \DateTime
     */
    public function getDateExp()
    {
        return $this->dateExp;
    }

    /**
     * Set renta
     *
     * @param boolean $renta
     *
     * @return RealtyObject
     */
    public function setRenta($renta)
    {
        $this->renta = $renta;

        return $this;
    }

    /**
     * Get renta
     *
     * @return boolean
     */
    public function getRenta()
    {
        return $this->renta;
    }

    /**
     * Set special
     *
     * @param boolean $special
     *
     * @return RealtyObject
     */
    public function setSpecial($special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * Get special
     *
     * @return boolean
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * Set employee
     *
     * @param \Application\Entity\Employee $employee
     *
     * @return RealtyObject
     */
    public function setEmployee(\Application\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \Application\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set plan
     *
     * @param \Application\Entity\ObjectImages $plan
     *
     * @return RealtyObject
     */
    public function setPlan(\Application\Entity\ObjectImages $plan = null)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \Application\Entity\ObjectImages
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set section
     *
     * @param \Application\Entity\Section $section
     *
     * @return RealtyObject
     */
    public function setSection(\Application\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \Application\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set realtyType
     *
     * @param \Application\Entity\RealtyType $realtyType
     *
     * @return RealtyObject
     */
    public function setRealtyType(\Application\Entity\RealtyType $realtyType = null)
    {
        $this->realtyType = $realtyType;

        return $this;
    }

    /**
     * Get realtyType
     *
     * @return \Application\Entity\RealtyType
     */
    public function getRealtyType()
    {
        return $this->realtyType;
    }
}
