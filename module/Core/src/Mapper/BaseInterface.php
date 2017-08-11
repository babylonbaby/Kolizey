<?php

namespace Core\Mapper;

use Doctrine\ORM\EntityRepository as EntityRepository;

interface BaseInterface
{
    /**
     * @param EntityRepository $repository
     * @return mixed
     */
    public function setRepository(EntityRepository $repository);

    /**
     * @return EntityRepository
     */
    public function getRepository();
} 