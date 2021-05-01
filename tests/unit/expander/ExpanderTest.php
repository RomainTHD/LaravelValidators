<?php

namespace PHPUnit\Webmozart\Assert;

use PHPUnit\Framework\TestCase;
use API\Expander;

require_once "../common.php";
require_once "../" . PATH . "expander/Expander.php";

/**
 * Expander unit test
 */
class ExpanderTest extends TestCase {
    /**
     * Test the processing
     * @see Expander::process
     */
    public function testProcess(): void {
        $properties = ["a", "*", "b", "c"];
        $validators = ["integer"];
        $actual = Expander::process($properties, $validators);

        $expected = <<<EOD
        {
            "type": "object",
            "validators": [
                "object"
            ],
            "properties": {
                "a": {
                    "type": "array",
                    "validators": [
                        "array"
                    ],
                    "items": {
                        "type": "object",
                        "validators": [
                            "object"
                        ],
                        "properties": {
                            "b": {
                                "type": "object",
                                "validators": [
                                    "object"
                                ],
                                "properties": {
                                    "c": {
                                        "validators": [
                                            "integer"
                                        ],
                                        "type": "leaf"
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
EOD;

        $this->assertEquals(preg_replace("/[\r\n ]*/", '', $expected), json_encode($actual));
    }

    /**
     * Test the merge with a name
     * @see Expander::mergeWithName
     */
    public function testMergeWithName(): void {
        $dst = [];

        $properties = ["a", "*", "b", "c"];
        $validators = ["integer"];
        $dst["x"] = Expander::process($properties, $validators);

        $properties = ["a", "*", "d", "e"];
        $validators = ["string"];
        $src = Expander::process($properties, $validators);

        Expander::mergeWithName("x", $dst, $src);

        $expected = <<<EOD
        {
            "x": {
                "type": "object",
                "validators": [
                    "object"
                ],
                "properties": {
                    "a": {
                        "type": "array",
                        "validators": [
                            "array"
                        ],
                        "items": {
                            "type": "object",
                            "validators": [
                                "object"
                            ],
                            "properties": {
                                "b": {
                                    "type": "object",
                                    "validators": [
                                        "object"
                                    ],
                                    "properties": {
                                        "c": {
                                            "validators": [
                                                "integer"
                                            ],
                                            "type": "leaf"
                                        }
                                    }
                                },
                                "d": {
                                    "type": "object",
                                    "validators": [
                                        "object"
                                    ],
                                    "properties": {
                                        "e": {
                                            "validators": [
                                                "string"
                                            ],
                                            "type": "leaf"
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
EOD;

        $this->assertEquals(preg_replace("/[\r\n ]*/", '', $expected), json_encode($dst));
    }
}
