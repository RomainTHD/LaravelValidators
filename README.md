# Laravel validators processing

### Input Example

```json
{
    "a.*.y.t": "integer",
    "a.*.y.u": "integer",
    "a.*.z": "object|keys:w,o",
    "b": "array",
    "b.c": "string",
    "b.d": "object",
    "b.d.e": "integer|min:5",
    "b.d.f": "string"
}
```

### Output Example
```json
{
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
                            "type": "leaf",
                            "validators": [
                                "integer"
                            ]
                        },
                        "u": {
                            "type": "leaf",
                            "validators": [
                                "integer"
                            ]
                        }
                    }
                },
                "z": {
                    "type": "object",
                    "validators": [
                        "object"
                    ],
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
    },
    "b": {
        "type": "object",
        "validators": [
            "object"
        ],
        "properties": {
            "c": {
                "validators": [
                    "string"
                ],
                "type": "leaf"
            },
            "d": {
                "type": "object",
                "validators": [
                    "object"
                ],
                "properties": {
                    "e": {
                        "validators": [
                            "integer",
                            "min:5"
                        ],
                        "type": "leaf"
                    },
                    "f": {
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
```
