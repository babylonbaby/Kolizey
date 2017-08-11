<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 15.02.17
 * Time: 2:13
 */

namespace Core\Form;

use DoctrineModule\Form\Element\Proxy;
use ReflectionMethod;

/**
 * Задача: расширить возможности списков, подгружаемых из БД, чтобы в режиме чтения форма не подгружала
 * большой список, а доставала только лишь необходимое значение для показа.
 *
 * Для этого 'disable_inarray_validator' => true  отключаем валидатор проверки в массиве по умолчанию
 * и ОБЯЗАТЕЛЬНО заменяем его другил _ленивым_ валидатором проверки наличия ключа в массиве
 *
 * Class DoctrineObjectSelectTrait
 * @package Core\Form
 */
trait DoctrineObjectSelectTrait {

    public function findOne($key)
    {
        /** @var \DoctrineModule\Form\Element\Proxy $proxy */
        $proxy = $this->getProxy();
        if ($this->isEmpty($proxy)) {
            $em = $proxy->getObjectManager();
            $findMethod = (array)$proxy->getFindMethod();
            if (!$findMethod) {
                throw new \RuntimeException('Необходим, но неопределен find_method');
            }
            if (!isset($findMethod['name'])) {
                throw new \RuntimeException('No method name was set');
            }
            $findMethodName   = $findMethod['name'];
            $findMethodParams = isset($findMethod['params']) ? array_change_key_case($findMethod['params']) : array();
            //===дополняем условие поиском по ключу ==
            $criteria = (array_key_exists('criteria', $findMethodParams)) ? $findMethodParams['criteria'] : [];
            $targetClass = $proxy->getTargetClass();
            $metadata         = $em->getClassMetadata($targetClass);
            $identifier       = $metadata->getIdentifierFieldNames();
            $criteria[reset($identifier)] = $key;
            $findMethodParams['criteria'] = $criteria;
            //===================


            $repository       = $em->getRepository($targetClass);

            if (!method_exists($repository, $findMethodName)) {
                throw new \RuntimeException(
                    sprintf(
                        'Method "%s" could not be found in repository "%s"',
                        $findMethodName,
                        get_class($repository)
                    )
                );
            }

            $r    = new ReflectionMethod($repository, $findMethodName);
            $args = array();
            foreach ($r->getParameters() as $param) {
                if (array_key_exists(strtolower($param->getName()), $findMethodParams)) {
                    $args[] = $findMethodParams[strtolower($param->getName())];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } elseif (!$param->isOptional()) {
                    throw new \RuntimeException(
                        sprintf(
                            'Required parameter "%s" with no default value for method "%s" in repository "%s"'
                            . ' was not provided',
                            $param->getName(),
                            $findMethodName,
                            get_class($repository)
                        )
                    );
                }
            }
            $objects = $r->invokeArgs($repository, $args);
            if (isset($objects[0])) {
                $object = $objects[0];
                $property = $proxy->getProperty();
                $getter = 'get' . ucfirst($property);

                if (!is_callable(array($object, $getter))) {
                    throw new \RuntimeException(
                        sprintf('Method "%s::%s" is not callable', $this->targetClass, $getter)
                    );
                }

                $label = $object->{$getter}();
                return $label;
            }
        }

        return null;
    }

    protected function isEmpty(Proxy $proxy)
    {
        //TODO проверка через рефлексию, что опции не загружены
        return true;
    }
}