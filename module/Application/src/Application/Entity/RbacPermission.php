<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RbacPermission
 *
 * @ORM\Table(name="rbac_permission")
 * @ORM\Entity
 */
class RbacPermission
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\RbacRole", mappedBy="permissionid")
     */
    private $roleid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roleid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return RbacPermission
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
     * Set title
     *
     * @param string $title
     *
     * @return RbacPermission
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add roleid
     *
     * @param \Application\Entity\RbacRole $roleid
     *
     * @return RbacPermission
     */
    public function addRoleid(\Application\Entity\RbacRole $roleid)
    {
        $this->roleid[] = $roleid;

        return $this;
    }

    /**
     * Remove roleid
     *
     * @param \Application\Entity\RbacRole $roleid
     */
    public function removeRoleid(\Application\Entity\RbacRole $roleid)
    {
        $this->roleid->removeElement($roleid);
    }

    /**
     * Get roleid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoleid()
    {
        return $this->roleid;
    }
}
