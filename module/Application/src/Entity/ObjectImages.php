<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectImages
 *
 * @ORM\Table(name="object_images", indexes={@ORM\Index(name="images_object_idx", columns={"object_id"})})
 * @ORM\Entity
 */
class ObjectImages
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

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
     * Set name
     *
     * @param string $name
     *
     * @return ObjectImages
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
     * Set object
     *
     * @param \Application\Entity\RealtyObject $object
     *
     * @return ObjectImages
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
