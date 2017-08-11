<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Housing
 *
 * @ORM\Table(name="housing", indexes={@ORM\Index(name="housing_complex_idx", columns={"complex_id"})})
 * @ORM\Entity
 */
class Housing
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
     * @ORM\Column(name="one_room_cost", type="integer", nullable=true)
     */
    private $oneRoomCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="two_room_cost", type="integer", nullable=true)
     */
    private $twoRoomCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="three_room_cost", type="integer", nullable=true)
     */
    private $threeRoomCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="four_room_cost", type="integer", nullable=true)
     */
    private $fourRoomCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="levels", type="integer", nullable=false)
     */
    private $levels;

    /**
     * @var \Application\Entity\ResidentialComplex
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\ResidentialComplex")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="complex_id", referencedColumnName="id")
     * })
     */
    private $complex;



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
     * Set oneRoomCost
     *
     * @param integer $oneRoomCost
     *
     * @return Housing
     */
    public function setOneRoomCost($oneRoomCost)
    {
        $this->oneRoomCost = $oneRoomCost;

        return $this;
    }

    /**
     * Get oneRoomCost
     *
     * @return integer
     */
    public function getOneRoomCost()
    {
        return $this->oneRoomCost;
    }

    /**
     * Set twoRoomCost
     *
     * @param integer $twoRoomCost
     *
     * @return Housing
     */
    public function setTwoRoomCost($twoRoomCost)
    {
        $this->twoRoomCost = $twoRoomCost;

        return $this;
    }

    /**
     * Get twoRoomCost
     *
     * @return integer
     */
    public function getTwoRoomCost()
    {
        return $this->twoRoomCost;
    }

    /**
     * Set threeRoomCost
     *
     * @param integer $threeRoomCost
     *
     * @return Housing
     */
    public function setThreeRoomCost($threeRoomCost)
    {
        $this->threeRoomCost = $threeRoomCost;

        return $this;
    }

    /**
     * Get threeRoomCost
     *
     * @return integer
     */
    public function getThreeRoomCost()
    {
        return $this->threeRoomCost;
    }

    /**
     * Set fourRoomCost
     *
     * @param integer $fourRoomCost
     *
     * @return Housing
     */
    public function setFourRoomCost($fourRoomCost)
    {
        $this->fourRoomCost = $fourRoomCost;

        return $this;
    }

    /**
     * Get fourRoomCost
     *
     * @return integer
     */
    public function getFourRoomCost()
    {
        return $this->fourRoomCost;
    }

    /**
     * Set levels
     *
     * @param integer $levels
     *
     * @return Housing
     */
    public function setLevels($levels)
    {
        $this->levels = $levels;

        return $this;
    }

    /**
     * Get levels
     *
     * @return integer
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * Set complex
     *
     * @param \Application\Entity\ResidentialComplex $complex
     *
     * @return Housing
     */
    public function setComplex(\Application\Entity\ResidentialComplex $complex = null)
    {
        $this->complex = $complex;

        return $this;
    }

    /**
     * Get complex
     *
     * @return \Application\Entity\ResidentialComplex
     */
    public function getComplex()
    {
        return $this->complex;
    }
}
