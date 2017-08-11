<?php
namespace Core\Utils;

/**
 * Класс для конвертации xml
 * Class XmlConverter
 * @package SyncData\Model
 */
class XmlConverter
{
    /**
     * Конвертация xml в ассоциативный массив
     * @param $xml
     * @param null $r
     * @return null
     */
    public function toArray($xml, $r = NULL)
    {
        if (is_string($xml)) {
            $sxi = new \SimpleXmlIterator($xml);
        } else {
            $sxi = $xml;
        }

        $tmp = array();
        $el = $sxi->getName();
        $namespaces = $sxi->getNameSpaces(true);
        foreach ($namespaces as $pre => $ns) {
            foreach ($sxi->children($ns) as $v) {
                $tmp = $this->toArray($v, $tmp, $pre);
            }
        }

        if (empty($tmp)) {
            $val = trim(strval($sxi));
            if (isset($r[$el])) {
                if (!is_array($r[$el])) {
                    $r[$el] = [$r[$el], $val];
                } else {
                    $r[$el][] = $val;
                }
            } else {
                $r[$el] = $val;
            }
        } else {
            if (isset($r[$el])) {
                if (!isset($r[$el][0])) {
                    $r[$el] = array($r[$el], $tmp);
                } else {
                    $r[$el][] = $tmp;
                }
            } else {
                $r[$el] = $tmp;
            }
        }

        return $r;
    }
}