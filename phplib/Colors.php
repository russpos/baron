<?php

/**
 * Colors
 *
 * A class for outputing colored strings to the console.
 */
class Colors {

    const BLACK = '0;30';
    const DARK_GRAY = '1;30';
    const BLUE = '0;34';
    const LIGHT_BLUE = '1;34';
    const GREEN = '0;32';
    const LIGHT_GREEN = '1;32';
    const CYAN = '0;36';
    const LIGHT_CYAN = '1;36';
    const RED = '0;31';
    const LIGHT_RED    = '1;31';
    const PURPLE       = '0;35';
    const LIGHT_PURPLE = '1;35';
    const BROWN        = '0;33';
    const YELLOW       = '1;33';
    const LIGHT_GRAY   = '0;37';
    const WHITE        = '1;37';

    const RESET_CODE = "\033[0m";

    /**
     * @var Color|null $instance
     */
    private static $instance = null;

    // private __construct() {{{ 
    /**
     * __construct
     * Private constructor. This is a no-op and only exists so that we
     * can ensure singleton.
     * @access private
     */
    private function __construct() {
    }
    // }}}

    // public getInstance() {{{
    /**
     * getInstance
     * Returns (or creates and returns) the single instance of Color
     * @static
     * @access public
     * @return Color
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    // }}}

    // public __call(color,args) {{{ 
    /**
     * __call
     * Helper function to allow for calling methods on the color
     * instance based off of the names of the color constants.
     *
     * For example:
     *
     * ```php
     * $color->green("String", $thing);
     * ```
     *
     * @param string $color Name of the color method to wrap the provided strings
     * @param array $args Collection of strings to be printed
     * @access public
     * @return string
     */
    public function __call($color, $args) {
        $string = implode(' ', $args);
        $color_code = constant('self::'. strtoupper($color));
        $reset = self::RESET_CODE;
        return "\033[{$color_code}m{$string}{$reset}";
    }
    // }}}
}
