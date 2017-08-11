<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 03.10.16
 * Time: 20:13
 */
namespace StateMachine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="dict_action", uniqueConstraints={
 *          @ORM\UniqueConstraint(name="code_idx", columns={"code", "`type`"})
 *      }
 * )
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
class DictAction {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment":"автоинкрементный идентификатор"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=64, nullable=false,
     *     options={"comment":"Уникальный в пределах стейтмашины код действия"})
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=true,
     *     options={"comment":"Имя действия"})
     */
    private $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
} 