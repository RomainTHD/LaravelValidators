<?php

namespace API;

/**
 * Initialization
 * @package API
 */
final class Init {
    /**
     * @var bool Debug or release
     */
    private static $DEBUG;

    /******************************************************************************************************************/

    /**
     * Main initialization
     * @param bool $DEBUG Debug or release
     */
    public static function init(bool $DEBUG): void {
        self::$DEBUG = $DEBUG;
        self::setErrors();
        self::setAssert();
        self::setHeaders();
    }

    /******************************************************************************************************************/

    /**
     * Sets the error level
     */
    private static function setErrors(): void {
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        error_reporting(self::$DEBUG ? -1 : 0);
    }

    /**
     * Sets the assert level
     */
    private static function setAssert(): void {
        assert_options(ASSERT_ACTIVE,       self::$DEBUG);
        assert_options(ASSERT_BAIL,         self::$DEBUG);
        assert_options(ASSERT_WARNING,      false);
        assert_options(ASSERT_QUIET_EVAL,   false);
    }

    /**
     * Set some common headers
     */
    private static function setHeaders(): void {
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Max-Age: 3600");
        header("Content-Security-Policy: default-src 'self'");
        header("Content-Type: application/json; charset=UTF-8");
        header("Strict-Transport-Security: max-age=3600");
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: DENY");
    }
}
