<?php
namespace Application\Hydrator\Realty;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as BaseHydrator;
use Application\Entity as EntityNS;
//use Zend\Hydrator\AbstractHydrator as BaseHydrator;

class Edit extends BaseHydrator
{

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $ret = parent::extract($object);
        return $ret;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        return parent::hydrate($data, $object);
    }


} 