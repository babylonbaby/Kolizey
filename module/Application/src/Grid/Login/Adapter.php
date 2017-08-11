<?php
namespace Application\Grid\Login;

use Grid\Service\JqGridDbalAdapter as BaseAdapter;
use Doctrine\DBAL\Query\QueryBuilder;
use Zend\Json\Expr;

class Adapter extends BaseAdapter
{
    /**
     * Построение запроса на базе элементов формы,
     * опция sql_from в форме определяет таблицу, из которой будет производиться выборка.
     * @param $inputData
     * @param bool $quote
     * @return QueryBuilder
     */
    public function getQuery($inputData, $quote=true)
    {
        $query = parent::getQuery($inputData, $quote);
        $query->leftJoin('t','rbac_role', 'r', 'r.id = t.roleId')
            ->addGroupBy('t.id')
        ;

        return $query;
    }

    public function getData($inputData)
    {
        $ret = parent::getData($inputData);
        foreach($ret['rows'] as &$row) {
            $row['actions'] = $this->createActionsData($row);
        }
        return $ret;
    }

    protected function createActionsData(array $row)
    {
        $form = $this->getForm();
        $baseFs = $form->getBaseFieldset();
        $actionsEl = $baseFs->get('actions');
        $ret = json_decode(json_encode($actionsEl), true);
        return $ret;
    }

} 