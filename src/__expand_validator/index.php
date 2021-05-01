<?php

use API\Init;
use API\Main;

require_once "Init.php";
require_once "Main.php";

/**
 * Debug or release
 */
const DEBUG = false;

Init::init(DEBUG);
Main::main(DEBUG);
