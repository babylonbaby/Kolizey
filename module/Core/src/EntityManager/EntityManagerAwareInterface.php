<?php
namespace Core\EntityManager;

use Doctrine\ORM\EntityManager;

interface EntityManagerAwareInterface
{
    /**
     * @param EntityManager $entityManager
     * @return mixed
     */
    public function setEntityManager(EntityManager $entityManager);
}