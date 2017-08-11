<?php
namespace Core\Mapper;

use Core\ServiceManager\ServiceLocatorAwareInterface;
use Core\ServiceManager\ServiceLocatorAwareTrait;
use Core\EntityManager\EntityManagerAwareInterface;
use Core\EntityManager\EntityManagerTrait;
use Core\Mapper\BaseInterface as BaseMapperInterface;
use Core\Mapper\BaseTrait as BaseMapperTrait;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class Base implements ServiceLocatorAwareInterface, EntityManagerAwareInterface, BaseMapperInterface
{
    use ServiceLocatorAwareTrait, EntityManagerTrait, BaseMapperTrait;

    /**
     *  Старт транзакции
     */
    public function beginTransaction()
    {
        $this->getEntityManager()->beginTransaction();
    }

    /**
     *  Сохранение транзакции
     */
    public function commit()
    {
        $this->getEntityManager()->commit();
    }

    /**
     * Откат транзакции
     */
    public function rollback()
    {
        $this->getEntityManager()->rollback();
    }

    /**
     * Синхронизация объектов с БД
     * @param object $entity
     */
    public function flush($entity = null)
    {
        $this->getEntityManager()->flush($entity);
    }

    /**
     * Регистрация объекта в EntityManager
     * @param object $entity
     */
    public function persist($entity) {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * Создание
     * @param array $data
     * @param boolean $hydrateByValue
     * @return mixed
     */
    public function create(array $data = null, $hydrateByValue = true)
    {
        $entityName = $this->getRepository()->getClassName();
        $entity = new $entityName();

        if($data != null) {
            $this->getHydrator($hydrateByValue)->hydrate($data, $entity);
        }

        return $entity;
    }

    /**
     * Сохранение объекта в БД
     * @param object $entity
     */
    public function save($entity)
    {
        $this->persist($entity);
        $this->flush($entity);
    }

    /**
     * Удаление объекта из EntityManager
     * @param object $entity
     */
    public function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
    }

    /**
     * Найти объект в БД по первичному ключу
     * @param $id
     * @return null|object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Найти список объектов по критерию
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Найти объект по критерию
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findOneBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Получить объект гидратора
     * @param bool $byValue If set to true, hydrator will always use entity's public API
     * @return DoctrineObject
     */
    public function getHydrator($byValue = true)
    {
        $entityName = $this->getRepository()->getClassName();
        return new DoctrineObject(
            $this->getEntityManager(), $byValue
        );
    }

    /**
     * Заполнить объект из массива данных
     * @param $entity
     * @param array $data
     * @param boolean $hydrateByValue
     * @return object
     */
    public function mergeFromArray($entity, array $data, $hydrateByValue = true)
    {
        return $this->getHydrator($hydrateByValue)->hydrate($data, $entity);
    }

    /**
     * Загрузка данных из файла
     * @param $filename - имя файла с данными
     */
    public function loadData($filename)
    {
        $entityName = $this->getRepository()->getClassName();
        $connection = $this->getEntityManager()->getConnection();
        $sql = "LOAD DATA LOCAL INFILE '" . getcwd() . ltrim($filename, '.') . "' INTO TABLE " .
            $this->getEntityManager()->getClassMetadata($entityName)->getTableName() .
            " CHARACTER SET UTF8 FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"'";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
    }
} 