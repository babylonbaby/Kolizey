<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RealtyCategories
 *
 * @ORM\Table(name="realty_categories")
 * @ORM\Entity
 */
class RealtyCategories
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
     * Many RealtyCategory have Many RealtyObject.
     * @ManyToMany(targetEntity="RealtyObject")
     * @JoinTable(name="object_categories",
     *      joinColumns={@JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="object_id", referencedColumnName="id")}
     *      )
     */    private $object;

     public function __construct()
     {
         $this->object = new ArrayCollection();
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
     * @return RealtyCategories
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
     * Add object
     *
     * @param \Application\Entity\RealtyObject $object
     *
     * @return RealtyCategories
     */
    public function addObject(\Application\Entity\RealtyObject $object = null)
    {
        $this->object[] = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return ArrayCollection;
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Remove object
     *
     * @return RealtyCategories
     */
    public function removeObject()
    {
        $this->object = new ArrayCollection();
        return $this;
    }


}
