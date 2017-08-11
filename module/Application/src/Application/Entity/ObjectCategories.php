<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectCategories
 *
 * @ORM\Table(name="object_categories", indexes={@ORM\Index(name="object_idx", columns={"object_id"}), @ORM\Index(name="category_idx", columns={"category_id"})})
 * @ORM\Entity
 */
class ObjectCategories
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
     * @var \Application\Entity\RealtyCategories
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RealtyCategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Application\Entity\RealtyObject
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RealtyObject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     * })
     */
    private $object;



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
     * Set category
     *
     * @param \Application\Entity\RealtyCategories $category
     *
     * @return ObjectCategories
     */
    public function setCategory(\Application\Entity\RealtyCategories $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Application\Entity\RealtyCategories
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set object
     *
     * @param \Application\Entity\RealtyObject $object
     *
     * @return ObjectCategories
     */
    public function setObject(\Application\Entity\RealtyObject $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \Application\Entity\RealtyObject
     */
    public function getObject()
    {
        return $this->object;
    }
}
