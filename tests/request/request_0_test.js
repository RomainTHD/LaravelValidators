/**
 * JS to compare expected results to API calls.
 * PhpStorm's HTTP client doesn't support ES6, thus those `var` or `function`.
 * Then, because a HTTP request can only be linked to a single JS file, I can't import a common JS file, so a big part
 * of the code is redundant.
 * Finally, I am aware that `JSON.stringify` doesn't sort the keys, so the expected variable can't be in any order.
 */

client.test("Successful request", function () {
    client.assert(response.status === 200, "Response status is not 200");
});

client.test("JSON response content-type", function () {
    var type = response.contentType.mimeType;
    client.assert(type === "application/json", "Expected 'application/json' but received '" + type + "'");
});

client.test("Expected result", function () {
    var expected = {
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
                    "y": {
                        "type": "object",
                        "validators": [
                            "object"
                        ],
                        "properties": {
                            "t": {
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
    };

    client.assert(JSON.stringify(response.body) === JSON.stringify(expected));
});
