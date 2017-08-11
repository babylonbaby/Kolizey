<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RealtyObject
 *
 * @ORM\Table(name="realty_object", indexes={@ORM\Index(name="object_type_idx", columns={"realty_type_id"}), @ORM\Index(name="object_employee_idx", columns={"employee_id"})})
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
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
     * @ORM\Column(name="price", type="string", length=255, nullable=true)
     */
    private $price;

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
     * @ORM\Column(name="rooms", type="string", length=255, nullable=true)
     */
    private $rooms;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=45, nullable=true)
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=45, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="home", type="string", length=45, nullable=true)
     */
    private $home;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=45, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="complex_name", type="string", length=255, nullable=true)
     */
    private $complexName;

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
     * @var \Application\Entity\Employee
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     * })
     */
    private $employee;

    /**
     * Many RealtyObject have Many RealtyCategory.
     * @ManyToMany(targetEntity="RealtyCategories")
     * @JoinTable(name="object_categories",
     *      joinColumns={@JoinColumn(name="object_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $category;

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
     * @var \Application\Entity\Section
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    private $section;

    /**
     * One Object has Many Images.
     * @OneToMany(targetEntity="ObjectImages", mappedBy="object")
     */
    private $images;

    public function __construct() {
        $this->category = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * @return \DateTime
     */
    public function getDateExp()
    {
        return $this->dateExp;
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
     * @param string $level
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
     * Add category
     *
     * @param \Application\Entity\RealtyCategories $category
     *
     * @return RealtyObject
     */
    public function addCategory(\Application\Entity\RealtyCategories $category = null)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return ArrayCollection;
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Remove category
     *
     * @return RealtyObject
     */
    public function removeCategory()
    {
        $this->category = new ArrayCollection();
        return $this;
    }

    public function hasCategory(RealtyCategories $category)
    {
        /** @var RealtyCategories $cat */
        foreach ($this->getCategory() as $cat) {
            if ($category->getName() == $cat->getName()) {
                return true;
            }
        }
        return false;
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

    /**
     * Add images
     *
     * @param \Application\Entity\ObjectImages $images
     *
     * @return RealtyObject
     */
    public function addImages(\Application\Entity\ObjectImages $images = null)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return ArrayCollection;
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Remove images
     *
     * @return RealtyObject
     */
    public function removeImages()
    {
        $this->images = new ArrayCollection();
        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param $destination
     * @return $this
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param $home
     * @return $this
     */
    public function setHome($home)
    {
        $this->home = $home;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplexName()
    {
        return $this->complexName;
    }

    /**
     * @param $complexName
     * @return $this
     */
    public function setComplexName($complexName)
    {
        $this->complexName = $complexName;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function setSection(Section $section)
    {
        $this->section = $section;
        return $this;
    }
}
