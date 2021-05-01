<?php

namespace PHPUnit\Webmozart\Assert;

use PHPUnit\Framework\TestCase;
use API\Expander\LeafExpander;

require_once "../common.php";
require_once "../" . PATH . "expander/LeafExpander.php";

/**
 * LeafExpander unit test
 */
class LeafExpanderTest extends TestCase {
    /**
     * Test the processing
     * @see LeafExpander::process
     */
    public function testProcess(): void {
        $properties = ["c"];
        $validators = ["integer", "keys:a,b"];
        $actual = LeafExpander::process($properties, $validators);

        $expected = <<<EOD
        {
            "validators": [
                "integer"
            ],
            "type": "object",
            "properties": {
                "a": {
                    "type": "leaf",
                    "validators": []
                },
                "b": {
                    "type": "leaf",
                    "validators": []
                }
            }
        }
EOD;

        $this->assertEquals(preg_replace("/[\r\n ]*/", '', $expected), json_encode($actual));
    }
}
