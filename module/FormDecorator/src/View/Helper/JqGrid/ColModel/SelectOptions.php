<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 01.02.17
 * Time: 4:40
 */

namespace FormDecorator\View\Helper\JqGrid\ColModel;

use Zend\Json\Expr;

class SelectOptions extends Expr
{
    private $opt = [];

    public function __construct($opt = array()) {
        $this->opt = $opt;
    }

    public function __toString() {
        $res = [];
        foreach($this->opt as $k => &$v) {
            if (is_array($v)) {
                $res[] = sprintf("'%s':'%s'", $v['value'], $v['label']);
            } else {
                $res[] = sprintf("'%s':'%s'", $k, $v);
            }
        }
        $ret = '{'. implode(',', $res) . '}';
        return $ret;
    }
}