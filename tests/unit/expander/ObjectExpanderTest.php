<?php

namespace PHPUnit\Webmozart\Assert;

use PHPUnit\Framework\TestCase;
use API\Expander\ObjectExpander;

require_once "../common.php";
require_once "../" . PATH . "expander/ObjectExpander.php";

/**
 * ObjectExpander unit test
 */
class ObjectExpanderTest extends TestCase {
    /**
     * Test the processing
     * @see ObjectExpander::process
     */
    public function testProcess(): void {
        $properties = ["a", "*", "b", "c"];
        $validators = ["integer"];
        $actual = ObjectExpander::process($properties, $validators);

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
}
