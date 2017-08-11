<?php
namespace Core\EntityManager;

use Doctrine\ORM\EntityManager as EntityManager;

trait EntityManagerTrait {
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Set entity manager
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}