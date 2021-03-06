<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 07.02.17
 * Time: 18:38
 */
namespace Grid\Hydrator\JqGrid2DoctrineDbal;

use Zend\Hydrator\AbstractHydrator as BaseHydrator;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Query\Expression\CompositeExpression;

class Having extends Where
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  QueryBuilder $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $composite = $this->hydrateGroup($data, $object);
        $object->andHaving($composite);
        return $composite;
    }
} 