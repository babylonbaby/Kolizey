<?php
namespace Application\Entity\Repository;

use Application\Entity\Rbacpermission;
use Doctrine\ORM\EntityRepository;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\AdapterInterface;


class RbacpermissionRepository extends EntityRepository
{
    public function getPermissionByName(string $name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}