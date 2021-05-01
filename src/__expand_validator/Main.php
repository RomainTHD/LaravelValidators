<?php

namespace API;

require_once "expander/Expander.php";

/**
 * Main class
 * @package API
 */
final class Main {
    /**
     * @var bool Debug or release
     */
    static $DEBUG;

    /******************************************************************************************************************/

    /**
     * Main function, cancellation point
     * @param bool $DEBUG Debug
     */
    public static function main(bool $DEBUG): void {
        self::$DEBUG = $DEBUG;
        self::populatePOST();

        if (!self::validateInput()) {
            // Invalid input, stop
            return;
        }

        $content = [];
        foreach ($_POST as $property => $validator) {
            $properties = explode('.', $property);
            $validators = explode('|', $validator);
            $name = array_shift($properties);
            $newBranch = Expander::process($properties, $validators);
            Expander::mergeWithName($name, $content, $newBranch);
        }

        echo json_encode((object) $content);
        // Cast used to send `{}` instead of `[]` if POST is empty
    }

    /******************************************************************************************************************/

    /**
     * Populate POST using `php://input`
     */
    private static function populatePOST(): void {
        // Avoid overriding $_POST
        if (empty($_POST)) {
            $tmp = json_decode(file_get_contents("php://input"), true);

            if (!is_null($tmp)) {
                $_POST = $tmp;
            }
        }
    }

    /**
     * Validate the input to avoid processing bad data.
     * We should NOT use a `die` because it would mess with the unit tests
     * @return bool Valid input or not
     */
    private static function validateInput(): bool {
        if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST" && !self::$DEBUG) {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed, use POST instead."]);
            return false;
        }

        foreach ($_POST as $property => $validator) {
            $properties = explode('.', $property);
            $validators = explode('|', $validator);

            if (empty($properties)) {
                // Shouldn't really happen, it would at least have one element
                http_response_code(400);
                echo json_encode([
                    "error" => "At least one property is empty.",
                    "validator" => $validator
                ]);
                return false;
            } else {
                foreach ($properties as $prop) {
                    if (empty($prop)) {
                        http_response_code(400);
                        echo json_encode([
                            "error" => "At least one property is empty.",
                            "validator" => $validator
                        ]);
                        return false;
                    }

                    if (!preg_match("/^([^.\s*]+?|\*)$/", $prop)) {
                        http_response_code(400);
                        echo json_encode([
                            "error" => "At least one property is malformed.",
                            "validator" => $validator,
                            "property" => $prop
                        ]);
                        return false;
                    }
                }
            }

            if (empty($validators)) {
                // Shouldn't really happen, it would at least have one element
                http_response_code(400);
                echo json_encode([
                    "error" => "At least one property has no validators.",
                    "property" => $property
                ]);
                return false;
            } else {
                foreach ($validators as $val) {
                    if (empty($val)) {
                        http_response_code(400);
                        echo json_encode([
                            "error" => "At least one validator is empty.",
                            "property" => $property
                        ]);
                        return false;
                    }

                    if (!preg_match("/^[^\s:]+(:([^\s:,]+,)*[^\s:,]+)?$/", $val)) {
                        // FIXME: Should we really invalidate `keys:a, b` and `a | b` ?
                        http_response_code(400);
                        echo json_encode([
                            "error" => "At least one validator is malformed.",
                            "validator" => $val,
                            "property" => $property
                        ]);
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
