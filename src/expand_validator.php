<?php

// Allows the other files in `__expand_validator` (like Main, Init...) to be easily hidden using only a `.htaccess`,
//  and the routes `/expand_validator` and `/expand_validator/` to be both accessed without conflicts
require_once "__expand_validator/index.php";
