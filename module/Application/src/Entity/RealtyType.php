<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * RealtyCategories
 *
 * @ORM\Table(name="realty_type")
 * @ORM\Entity
 */
class RealtyType
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

    /*
     * @ORM\OneToMany(targetEntity="RealtyObject", mappedBy="realtyType")
     */
    private $realtyObject;

    public function __construct()
    {
        $this->realtyObject = new ArrayCollection();
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
     * @return RealtyType
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
     * @return ArrayCollection
     */
    public function getRealtyObjects()
    {
        return $this->realtyObject;
    }

    /**
     * @param ArrayCollection $collection
     * @return $this
     */
    public function setRealtyObjects(ArrayCollection $collection)
    {
        $this->realtyObject = $collection;
        return $this;
    }


}
