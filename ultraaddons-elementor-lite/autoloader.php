<?php

namespace UltraAddons;

defined('ABSPATH') || exit;

/**
 * Autoloader for UltraAddons Elementor Lite. All class will call from here by AutoLoader
 *
 * @author Saiful Islam<codersaiful@gmail.com>
 * @since 1.0
 * @package UltraAddons
 * @link https://www.php.net/manual/en/language.oop5.autoload.php Autoloader Function
 */
class Autoloader {
    
    /**
     * Obviously should be lower char
     *
     * @var type Array
     */
    private static $parent_namespace = ['modules','includes','assets'];

    /**
     * Run autoloader.
     *
     * Register a function as `__autoload()` implementation.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function run() {
        spl_autoload_register([ __CLASS__ , 'autoload' ]);
    }

    /**
     * Autoload.
     *
     * For a given class, check if it exist and load it.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @param string $class Class name.
     */
    private static function autoload( $class ) {
        
        if (0 !== strpos( $class, __NAMESPACE__ ) ) {
            return;
        }


        $filename = strtolower(
                preg_replace(
                        ['/\b' . __NAMESPACE__ . '\\\/', '/_/', '/\\\/'], ['', '-', DIRECTORY_SEPARATOR], $class
                )
        );
        
        $full_class_name = strtolower( preg_replace('/\\\/', DIRECTORY_SEPARATOR, $class ) );

        
        $filepath = ULTRA_ADDONS_DIR . 'inc/' . $filename . '.php';
        $filepath = realpath( $filepath );

        if ( is_readable( $filepath ) ) {
            require_once $filepath;
            return;
        }
        
        /**
         * Pro file Integration 
         * 
         * ************************
         * First we will find in free version
         * then it will search in pro version
         * 
         * amd we will try to open each file using auto loader
         * ************************
         * 
         * @since 1.0.27
         */
        if( ! defined( 'ULTRA_ADDONS_PRO_DIR' ) ) return;
        
        $filepath = ULTRA_ADDONS_PRO_DIR . 'inc/' . $filename . '.php';
        $filepath = realpath( $filepath );

        if ( is_readable( $filepath ) ) {
            require_once $filepath;
            return;
        }

    }

}

Autoloader::run();