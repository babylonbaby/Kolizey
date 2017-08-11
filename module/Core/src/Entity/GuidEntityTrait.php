<?php
namespace Core\Entity;

use Core\Utils\GuidGenerator;
use Doctrine\ORM\Mapping as ORM;

/**
 * !!! Для использование необходимо добавить в Entity @HasLifecycleCallbacks !!!
 * Class GuidEntityTrait
 * @package Core\Entity
 *
 * @method getId()
 */
trait GuidEntityTrait
{
    /**
     * GUID
     * @var string
     * @ORM\Column(name="guid", type="string", length=36, nullable=false)
     */
    protected $guid;

    /**
     * @param mixed $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * @return mixed
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /** @ORM\PreFlush */
    public function beforeSave()
    {
        if (!$this->getGuid()) {
            $this->setGuid(GuidGenerator::generate());
        }
    }
}