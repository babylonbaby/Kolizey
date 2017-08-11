<?php

namespace Application\Hydrator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as BaseHydrator;
use Application\Entity\User;
/**
 * Class UserHydrator
 * @package Application\Hydrator
 */
class UserHydrator extends BaseHydrator
{
    /**
     * @param User $object
     * @return array
     */
    public function extract($object)
    {
        $data = parent::extract($object);

        if ($object instanceof User) {
            if ($object->getActive()->getTitle() == User::STATUS_ACTIVE) {
                $data['active'] = '1';
            } else {
                $data['active'] = '0';
            }
            $data['lastname'] = $object->getLastName();
            $data['firstName'] = $object->getFirstName();
            $data['middleName'] = $object->getMiddleName();
            $data['role'] = $object->getRoleId()->getRoleId();
            $data['login'] = $object->getUserName();
            $data['password'] = $object->getPassword();
        }
        return $data;
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        return parent::hydrate($data, $object);
    }
}
