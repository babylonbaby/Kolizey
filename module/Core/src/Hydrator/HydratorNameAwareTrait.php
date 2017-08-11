<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 14.02.17
 * Time: 11:01
 */
namespace Core\Hydrator;

trait HydratorNameAwareTrait
{
    protected $hydratorName;

    /**
     * Возвращает имя класса гидратора
     * @return string
     */
    public function getHydratorName()
    {
        return $this->hydratorName;
    }

    /**
     * @param mixed $hydratorName
     * @return self
     */
    public function setHydratorName($hydratorName)
    {
        $this->hydratorName = $hydratorName;
        return $this;
    }
} 