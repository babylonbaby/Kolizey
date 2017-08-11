<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RbacRole
 *
 * @ORM\Table(name="rbac_role")
 * @ORM\Entity
 */
class RbacRole
{
    const ROLE_ADMINISTRATOR = 'administrator';

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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\RbacPermission", inversedBy="roleid")
     * @ORM\JoinTable(name="rbac_role_permission",
     *   joinColumns={
     *     @ORM\JoinColumn(name="roleId", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permissionId", referencedColumnName="id")
     *   }
     * )
     */
    private $permissionid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permissionid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return RbacRole
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
     * @return RbacRole
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
     * Add permissionid
     *
     * @param \Application\Entity\RbacPermission $permissionid
     *
     * @return RbacRole
     */
    public function addPermissionid(\Application\Entity\RbacPermission $permissionid)
    {
        $this->permissionid[] = $permissionid;

        return $this;
    }

    /**
     * Remove permissionid
     *
     * @param \Application\Entity\RbacPermission $permissionid
     */
    public function removePermissionid(\Application\Entity\RbacPermission $permissionid)
    {
        $this->permissionid->removeElement($permissionid);
    }

    /**
     * Get permissionid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissionid()
    {
        return $this->permissionid;
    }
}
