<?php

namespace PHPUnit\Webmozart\Assert;

use PHPUnit\Framework\TestCase;
use API\Main;

require_once "./common.php";
require_once PATH . "Main.php";

/**
 * Main unit test
 */
class MainTest extends TestCase {
    /**
     * Test for empty validator input
     * @example ""
     * @see Main::validateInput
     */
    public function testValidationEmptyValidatorInput_0(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => ""];
        $this->expectOutputString('{"error":"At least one validator is empty.","property":"a.b.*.c"}');
        Main::main(false);
    }

    /**
     * Test for empty validator input
     * @example "a|"
     * @see Main::validateInput
     */
    public function testValidationEmptyValidatorInput_1(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => "integer|"];
        $this->expectOutputString('{"error":"At least one validator is empty.","property":"a.b.*.c"}');
        Main::main(false);
    }

    /**
     * Test for empty validator input
     * @example "a||b"
     * @see Main::validateInput
     */
    public function testValidationEmptyValidatorInput_2(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => "integer||string"];
        $this->expectOutputString('{"error":"At least one validator is empty.","property":"a.b.*.c"}');
        Main::main(false);
    }

    /**
     * Test for malformed validator input
     * @example "keys:"
     * @see Main::validateInput
     */
    public function testValidationMalformedValidatorInput_0(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => "keys:"];
        $this->expectOutputString('{"error":"At least one validator is malformed.","validator":"keys:","property":"a.b.*.c"}');
        Main::main(false);
    }

    /**
     * Test for malformed validator input
     * @example "keys:a b"
     * @see Main::validateInput
     */
    public function testValidationMalformedValidatorInput_1(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => "keys:a b"];
        $this->expectOutputString('{"error":"At least one validator is malformed.","validator":"keys:a b","property":"a.b.*.c"}');
        Main::main(false);
    }

    /**
     * Test for malformed validator input
     * @example "keys:a, b"
     * @see Main::validateInput
     */
    public function testValidationMalformedValidatorInput_2(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => "keys:a, b"];
        $this->expectOutputString('{"error":"At least one validator is malformed.","validator":"keys:a, b","property":"a.b.*.c"}');
        Main::main(false);
    }

    /**
     * Test for malformed validator input
     * @example "a | b"
     * @see Main::validateInput
     */
    public function testValidationMalformedValidatorInput_3(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b.*.c" => "string | integer"];
        $this->expectOutputString('{"error":"At least one validator is malformed.","validator":"string ","property":"a.b.*.c"}');
        Main::main(false);
    }

    /******************************************************************************************************************/

    /**
     * Test for empty validator input
     * @example ""
     * @see Main::validateInput
     */
    public function testValidationEmptyPropertyInput_0(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["" => "integer"];
        $this->expectOutputString('{"error":"At least one property is empty.","validator":"integer"}');
        Main::main(false);
    }

    /**
     * Test for empty validator input
     * @example ".a.b"
     * @see Main::validateInput
     */
    public function testValidationEmptyPropertyInput_1(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = [".a.b" => "integer"];
        $this->expectOutputString('{"error":"At least one property is empty.","validator":"integer"}');
        Main::main(false);
    }

    /**
     * Test for empty validator input
     * @example "a..b"
     * @see Main::validateInput
     */
    public function testValidationEmptyPropertyInput_2(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a..b" => "integer"];
        $this->expectOutputString('{"error":"At least one property is empty.","validator":"integer"}');
        Main::main(false);
    }

    /**
     * Test for empty validator input
     * @example "a.b."
     * @see Main::validateInput
     */
    public function testValidationEmptyPropertyInput_3(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.b." => "integer"];
        $this->expectOutputString('{"error":"At least one property is empty.","validator":"integer"}');
        Main::main(false);
    }

    /**
     * Test for malformed validator input
     * @example "a.**.b"
     * @see Main::validateInput
     */
    public function testValidationMalformedPropertyInput_0(): void {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = ["a.**.b" => "integer"];
        $this->expectOutputString('{"error":"At least one property is malformed.","validator":"integer","property":"**"}');
        Main::main(false);
    }

    /******************************************************************************************************************/

    /**
     * Test the main function to check if everything works as intended
     * @see Main::main
     */
    public function testWorksAsIntended(): void {
        $_POST = ["a.b.*.c" => "integer"];
        $_SERVER["REQUEST_METHOD"] = "POST";

        $expected = <<<EOD
        {
            "a": {
                "type": "object",
                "validators": [
                    "object"
                ],
                "properties": {
                    "b": {
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
EOD;

        $this->expectOutputString(preg_replace("/[\r\n ]*/", '', $expected));
        Main::main(false);
    }
}
