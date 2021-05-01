<?php

namespace API\Expander;

use API\Expander;

require_once "Expander.php";
require_once "ExpanderType.php";

/**
 * Object expander
 * @package API\Expander
 */
class ObjectExpander extends Expander {
    public static function process(array &$properties, array &$validators): array {
        $name = array_shift($properties);
        return [
            "type" => ExpanderType::OBJECT,
            "validators" => [
                ExpanderType::OBJECT
            ],
            "properties" => [
                $name => parent::process($properties, $validators)
            ]
        ];
    }

    protected static function merge(array &$dst, array &$src): void {
        if ($dst["type"] === $src["type"]) {
            // Same branch type, keep searching
            $k = key($src["properties"]);
            parent::mergeWithName($k, $dst["properties"], $src["properties"][$k]);
        } else {
            unset($dst["items"]);
            $dst["type"] = $src["type"];
            $dst["validators"] = $src["validators"];
            $dst["properties"] = $src["properties"];
        }
    }
}
