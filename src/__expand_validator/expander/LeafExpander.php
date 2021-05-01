<?php

namespace API\Expander;

use API\Expander;

require_once "Expander.php";
require_once "ExpanderType.php";

/**
 * Leaf expander
 * @package API\Expander
 */
class LeafExpander extends Expander {
    public static function process(array &$properties, array &$validators): array {
        $data = [
            "validators" => self::processValidators($validators)
        ];

        $properties = self::processKeysValidators($validators);
        if (empty($properties)) {
            // Normal case where the leaf really is a leaf
            $data["type"] = ExpanderType::LEAF;
        } else {
            // When the element has a "keys" validator
            $data["type"] = ExpanderType::OBJECT;
            $data["properties"] = $properties;
        }

        return $data;
    }

    protected static function merge(array &$dst, array &$src): void {
        assert($dst["type"] !== $src["type"], "Same leaves");
        // Shouldn't happen, because it would imply the JSON has duplicate properties / keys

        unset($dst["items"]);
        unset($dst["properties"]);
        $dst["type"] = $src["type"];
        $dst["validators"] = $src["validators"];
    }

    /******************************************************************************************************************/

    /**
     * Process the validators
     * @param array $validators Raw validators
     * @return array Processed validators
     */
    private static function processValidators(array $validators): array {
        $validatorsOut = [];
        foreach ($validators as $validator) {
            $content = explode(':', $validator);
            if (count($content) !== 2 || $content[0] !== "keys") {
                // We will process `keys` later
                array_push($validatorsOut, $validator);
            }
        }

        return $validatorsOut;
    }

    /**
     * Process the eventual validators `keys`
     * @param array $validators Validators
     * @return array New properties
     */
    private static function processKeysValidators(array $validators): array {
        $properties = [];

        foreach ($validators as $validator) {
            $content = explode(':', $validator);
            if (count($content) === 2 && $content[0] === "keys") {
                $keys = explode(',', $content[1]);
                foreach ($keys as $key) {
                    $properties[$key] = [
                        "type" => ExpanderType::LEAF,
                        "validators" => []
                    ];
                }
            }
        }

        return $properties;
    }
}
