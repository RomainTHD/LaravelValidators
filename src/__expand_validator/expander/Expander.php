<?php

namespace API;

use API\Expander\ArrayExpander;
use API\Expander\LeafExpander;
use API\Expander\ObjectExpander;
use API\Expander\ExpanderType;

require_once "ArrayExpander.php";
require_once "LeafExpander.php";
require_once "ObjectExpander.php";
require_once "ExpanderType.php";

/**
 * Main expander
 * @package API
 */
abstract class Expander {
    /**
     * Generic processing of an element
     * @param array $properties Properties of the element, like `["a", "*", "b"]`
     * @param array $validators Validators, like `["object", "keys:w,o"]`
     * @return array Processed element
     */
    public static function process(array &$properties, array &$validators): array {
        if (empty($properties)) {
            return LeafExpander::process($properties, $validators);
        } else if ($properties[0] === '*') {
            return ArrayExpander::process($properties, $validators);
        } else {
            return ObjectExpander::process($properties, $validators);
        }
    }

    /**
     * Merge two branches, like `a.b.c` and `a.b.d`.
     * `$dst` is usually more "filled" than `$src`,
     * because we're merging the second one in the first one.
     * This function is the "main" one and takes a name.
     * @param string $name Branch name
     * @param array $dst Full branch, destination
     * @param array $src Single branch, origin
     */
    public static function mergeWithName(string $name, array &$dst, array &$src): void {
        if (isset($dst[$name])) {
            switch ($src["type"]) {
                case ExpanderType::ARRAY:
                    ArrayExpander::merge($dst[$name], $src);
                    break;

                case ExpanderType::OBJECT:
                    ObjectExpander::merge($dst[$name], $src);
                    break;

                case ExpanderType::LEAF:
                    LeafExpander::merge($dst[$name], $src);
                    break;

                default:
                    // Should really not happen, unless the developer made a typo
                    die(json_encode(["error" => "Invalid branch type."]));
            }
        } else {
            // We just append the new branch
            $dst[$name] = $src;
        }
    }

    /**
     * Merge two elements, like `a.b.c` and `a.b.d`.
     * `$dst` is usually more "filled" than `$src`, because we're merging the second one in the first one.
     * If the two elements can't be merged, it recursively searches for something else to merge with.
     * @param array $dst Full element, destination
     * @param array $src Single element, origin
     */
    protected static abstract function merge(array &$dst, array &$src): void;
}
