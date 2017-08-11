<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 14.02.17
 * Time: 11:01
 */
namespace Core\Hydrator;

use Zend\Hydrator\HydratorAwareInterface;

interface HydratorNameAwareInterface extends HydratorAwareInterface
{
    /**
     * Возвращает имя класса гидратора
     * @return string
     */
    public function getHydratorName();
} 