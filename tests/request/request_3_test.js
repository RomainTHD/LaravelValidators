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
        "b": {
            "validators": [
                "object"
            ],
            "type": "object",
            "properties": {
                "c": {
                    "validators": [
                        "string"
                    ],
                    "type": "leaf"
                }
            }
        }
    };

    client.assert(JSON.stringify(response.body) === JSON.stringify(expected));
});
