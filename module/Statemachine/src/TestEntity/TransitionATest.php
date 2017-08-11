<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 11:30
 */

namespace StateMachine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Таблица-A переходов для ентити Test
 * TransitionATest
 *
 * @ORM\Table(name="transition_a_test",  indexes={
 *          @ORM\Index(name="src", columns={"src"}),
 *          @ORM\Index(name="action", columns={"action"})
 *      }
 * )
 * @ORM\Entity
 *
 */
class TransitionATest implements TransitionAInterface
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
     * @var TestStateDict
     *
     * @ORM\ManyToOne(targetEntity="TestStateDict")
     * @ORM\JoinColumn(name="src", referencedColumnName="id", nullable=false)
     */
    private $src;

    /**
     * @var TestActionDict
     *
     * @ORM\ManyToOne(targetEntity="TestActionDict")
     * @ORM\JoinColumn(name="action", referencedColumnName="id", nullable=false)
     */
    private $action;

    /**
     * @var string
     * @ORM\Column(name="condition", type="string", nullable=true,
     *      options={ "comment": "Валидатор доступности данного действия"} )
     */
    private $condition;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="TransitionBTest",
     *  mappedBy="transitionA")
     */
    private $transitionsB;

    public function __construct()
    {
        $this->transitionsB = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return TestActionDict
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param TestActionDict $action
     */
    public function setAction(TestActionDict $action)
    {
        $this->action = $action;
    }

    /**
     * @return TestStateDict
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param TestStateDict $src
     * @return self
     */
    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     * @return self
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTransitionsB()
    {
        return $this->transitionsB;
    }

    /**
     * @param ArrayCollection $transitionsB
     * @return self
     */
    public function setTransitionsB($transitionsB)
    {
        $this->transitionsB = $transitionsB;
        return $this;
    }
} 