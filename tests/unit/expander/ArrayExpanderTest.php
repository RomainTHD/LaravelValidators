<?php

namespace PHPUnit\Webmozart\Assert;

use PHPUnit\Framework\TestCase;
use API\Expander\ArrayExpander;

require_once "../common.php";
require_once "../" . PATH . "expander/ArrayExpander.php";

/**
 * ArrayExpander unit test
 */
class ArrayExpanderTest extends TestCase {
    /**
     * Test the processing
     * @see ArrayExpander::process
     */
    public function testProcess(): void {
        $properties = ["a", "*", "b", "c"];
        $validators = ["integer"];
        $actual = ArrayExpander::process($properties, $validators);

        $expected = <<<EOD
        {
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
        }
EOD;

        $this->assertEquals(preg_replace("/[\r\n ]*/", '', $expected), json_encode($actual));
    }
}
