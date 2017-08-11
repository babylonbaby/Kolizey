<?php

namespace Core\Utils;


/**
 * Class ArrayToXml
 * @package Core\Utils
 */
class ArrayToXml
{
    /**
     * @var array
     */
    private $mapNamesChildElements;

    /**
     * @var string
     */
    private $rootElement;

    /**
     * ArrayToXml constructor.
     * @param $rootElement
     * @param $mapNamesChildElements
     */
    public function __construct($rootElement = 'xml', $mapNamesChildElements = array())
    {
        $this->rootElement = $rootElement;
        $this->mapNamesChildElements = $mapNamesChildElements;
    }

    /**
     * @param $data
     * @return \DOMDocument
     * @throws \Exception
     */
    public function convert($data)
    {
        $xml = new \DOMDocument('1.0', "UTF-8");
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $root = $xml->createElement($this->rootElement);
        $xml->appendChild($root);

        $this->fromArray($data, $xml, $root);

        return $xml;
    }

    /**
     * @param $array
     * @param \DOMDocument $xml
     * @param \DOMElement $root
     * @throws \Exception
     */
    public function fromArray($array, $xml, $root)
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (is_int($key)) {
                        $parentElementName = $root->tagName;
                        if (array_key_exists($parentElementName, $this->mapNamesChildElements)) {
                            $key = $this->mapNamesChildElements[$parentElementName];
                        } else {
                            throw new \Exception('Map not available for the value ' . $parentElementName);
                        }
                    }
                    if(!empty($value) && !empty($key)) {
                        $node = $xml->createElement($key);
                        $root->appendChild($node);

                        $this->fromArray($value, $xml, $node);
                    }
                } else {
                    // Если значение не определено и не является bool
                    if(!is_bool($value) && !$value && $value != '0')
                        continue;

                    $node = $xml->createElement($key);
                    //NB баг ПХП (при присвоении значений содержащих & вызывает Warning)
                    $node->appendChild($xml->createTextNode($value));

                    if (!empty($node))
                        $root->appendChild($node);

                }
            }
        }
    }
}