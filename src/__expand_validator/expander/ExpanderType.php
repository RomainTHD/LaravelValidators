<?php

namespace API\Expander;

/**
 * Expander types, avoid hardcoded strings
 * @package API\Expander
 */
class ExpanderType {
    /**
     * Leaf
     */
    const LEAF = "leaf";

    /**
     * Object
     */
    const OBJECT = "object";

    /**
     * Array
     */
    const ARRAY = "array";
}
