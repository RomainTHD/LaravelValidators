<?php

namespace API\Expander;

use API\Expander;

require_once "Expander.php";
require_once "ExpanderType.php";

/**
 * Array expander
 * @package API\Expander
 */
class ArrayExpander extends Expander {
    public static function process(array &$properties, array &$validators): array {
        // At this point, the properties shouldn't be empty
        assert(!empty($properties), "Empty properties");

        if ($properties[0] === '*') {
            // Skip the possible '*'
            array_shift($properties);
        }

        return [
            "type" => ExpanderType::ARRAY,
            "validators" => [
                ExpanderType::ARRAY
            ],
            "items" => parent::process($properties, $validators)
        ];
    }

    protected static function merge(array &$dst, array &$src): void {
        if ($dst["type"] === $src["type"]) {
            // Same branch type, keep searching
            assert(
                $dst["items"]["type"] === $src["items"]["type"] && $src["items"]["type"] === ExpanderType::OBJECT,
                "Item type is not 'object'"
            );
            // FIXME: Is the type really always `object` ?

            $k = key($src["items"]["properties"]);
            parent::mergeWithName($k, $dst["items"]["properties"], $src["items"]["properties"][$k]);
        } else {
            // Different elements, merge
            unset($dst["properties"]);
            $dst["type"] = $src["type"];
            $dst["validators"] = $src["validators"];
            $dst["items"] = $src["items"];
        }
    }
}
