// @see request_0.tests.js

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
                            },
                            "u": {
                                "validators": [
                                    "integer"
                                ],
                                "type": "leaf"
                            }
                        }
                    },
                    "z": {
                        "validators": [
                            "object"
                        ],
                        "type": "object",
                        "properties": {
                            "w": {
                                "type": "leaf",
                                "validators": []
                            },
                            "o": {
                                "type": "leaf",
                                "validators": []
                            }
                        }
                    }
                }
            }
        }
    };

    client.assert(JSON.stringify(response.body) === JSON.stringify(expected));
});
