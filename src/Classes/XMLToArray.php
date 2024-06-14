<?php

namespace Zirsakht\Responder\Classes;

use DOMDocument;

class XMLToArray
{
    /**
     * Convert Valid XML to an array.
     *
     * @param $xml
     * @param bool $outputRoot
     * @return array
     */
    public static function convert($xml, bool $outputRoot = false): array
    {
        $array = self::XMLStringToArray($xml);
        if (!$outputRoot && array_key_exists('@root', $array)) {
            unset($array['@root']);
        }

        return $array;
    }

    protected static function XMLStringToArray($value): array
    {
        $document = new DOMDocument();
        $document->loadXML($value);
        $root = $document->documentElement;
        $output = self::DOMNodeToArray($root);
        $output['@root'] = $root->tagName;

        return $output;
    }

    protected static function DOMNodeToArray($node): array|string
    {
        $output = [];

        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $value = self::domNodeToArray($child);

                    if (isset($child->tagName)) {
                        $tag = $child->tagName;

                        if (!isset($output[$tag])) {
                            $output[$tag] = [];
                        }

                        $output[$tag][] = $value;
                    } elseif ($value || $value === '0') {
                        $output = (string) $value;
                    }
                }

                if ($node->attributes->length && !is_array($output)) { // Has attributes but isn't an array
                    $output = ['@content' => $output]; // Change output into an array.
                }

                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $attributes = [];

                        foreach ($node->attributes as $attrName => $attrNode) {
                            $attributes[$attrName] = (string) $attrNode->value;
                        }

                        $output['@attributes'] = $attributes;
                    }

                    foreach ($output as $tag => $value) {
                        if (is_array($value) && count($value) == 1 && $tag != '@attributes') {
                            $output[$tag] = $value[0];
                        }
                    }
                }
                break;
        }

        return $output;
    }
}
