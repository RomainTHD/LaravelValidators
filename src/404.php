<?php

header("Content-Type: application/json; charset=UTF-8");
http_response_code(404);

echo json_encode(["error" => "API endpoint not found."]);
