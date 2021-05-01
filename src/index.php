<?php

header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
http_response_code(303); // See Other

echo json_encode(["warning" => "Use the route `/expand_validator`."]);
