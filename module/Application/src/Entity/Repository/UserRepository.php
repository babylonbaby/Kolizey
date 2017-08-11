<?php
namespace Application\Entity\Repository;

use Application\Entity\User;
use Doctrine\ORM\EntityRepository;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\AdapterInterface;


class UserRepository extends EntityRepository
{
    public function findOneByUserName($userName){
        return $this->findOneBy(['username' => $userName, 'active' => 1]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPermission($name)
    {
        if ($name == '*') {
            return true;
        }
        foreach ($this->getRoleId()->getPermissionId() as $permission) {
            if ($permission->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRole($name)
    {
        if ($name == '*') {
            return true;
        }
        if ($this->getRoleId()->getName() == $name) {
            return true;
        }
        return false;
    }

}