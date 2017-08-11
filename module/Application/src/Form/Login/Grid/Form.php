<?php
namespace Application\Form\Login\Grid;

use Zend\Form\Form as BaseForm;
use FormDecorator\View\Helper\JqGrid as JqGridNS;
use Zend\Json\Expr;

class Form extends BaseForm
{
    public function init()
    {
        parent::init();

        $this->add([
                'type' => BaseFieldset::class,
                'name' => 'base',
            ]);
        $this->setBaseFieldset($this->get('base'));
        $this->setAttribute('action', '/user/data');
        $this->initGrid();
    }

    protected function initGrid()
    {
        $this->setOption('sql_from', 'user');
        $this->setOption('jqGrid', [
                'sortable' => true,
                "datatype" => "json",
                "multiSort" => false,
                "viewrecords" => true,
                "autowidth" => true,
                //'forceFit' => true,
                'shrinkToFit' => true,
                //'width' => '1600',
                'height' => 'auto',
                "pager" => new Expr('pagerSelector'),
                'sortname' => 'name',
                'rowList' => [ 10, 20, 30],
                'rowNum' => 20,

            ]);

        $this->initGridMethods();
    }

    protected function initGridMethods()
    {
        $this->setOption('jqGridMethods', [
                new JqGridNS\Method('filterToolbar', [
                    ["searchOnEnter" => true, "stringResult" => true, "groupOp" => 'AND', "searchOperators" => false,]
                ]),
                new JqGridNS\Method('navGrid',[
                    new Expr('pagerSelector'),
                    ['edit' =>false, 'add' => false, 'del' => false, 'refresh' => true, 'search' => false,],
                    (object)[], (object)[], (object)[],
                    ['multipleSearch' => true, 'multipleGroup' => true]
                ]),
            ]);
    }
} 