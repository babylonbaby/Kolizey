<?php
namespace Core\Mapper;

use Doctrine\ORM\EntityRepository as EntityRepository;

trait BaseTrait {
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param $repository
     * @return $this
     */
    public function setRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}