<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
//use UltraAddons\WP\Custom_Fonts_Taxonomy;
use UltraAddons\Core\Custom_Fonts_Handle as Fonts;

defined('ABSPATH') || die();

/**
 * ******************
 * EXTENSION
 * ******************
 * it's Actually a Extension 
 * But it will handle based on our Extension list.
 * It will be enable always. If a user want to do it
 * 
 * Custom Font will available in Extension list from Dashboard
 * 
 * @since 1.1.0.3
 * @author Saiful Islam<codersaiful@gmail.com>
 * @package UltraAddons
 */
class Custom_Fonts{


    public static $fonts = null;
    public static $font_group_key;
    
    /**
     * Initializing method
     * 
     * Handle like Extension and
     * Mainly for front End.
     * I mean: fonts choosing and doing something from Elementor Edit Screen
     * 
     * @since 1.1.0.3
     * @author Saiful
     */
    public static function init() {
        self::$font_group_key = self::get_font_group();
        add_filter( 'elementor/fonts/groups', array( __CLASS__, 'font_group' ) );
        add_filter( 'elementor/fonts/additional_fonts', array( __CLASS__, 'additional_fonts' ) );


        /**
         * To added Font file upload and support from 
         * here, I have added wp builtin filter
         * 
         * @since 1.1.0.9
         * @author Saiful Islam <codersaiful@gmail.com>
         * 
         * @date 25/1/2022
         */
        add_filter('upload_mimes', [__CLASS__, 'custom_upload_mimes']);

        /**
         * Render All selected Custom field here.
         */
        \UltraAddons\Classes\Custom_Fonts_Render::init();

        
    }

    /**
     * Supporting all font file 
     * inside wp using filter
     * 
     * If not able to upload your font file, use following constant
     * define('ALLOW_UNFILTERED_UPLOADS', true);
     * 
     * ********************************
     * application/x-font-woff2,application/x-font-woff,application/x-font-ttf,application/x-font-otf'
     * ********************************
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @since 1.1.0.9
     *
     * @param Array $existing_mimes
     * @return Array
     */
    public static function custom_upload_mimes ( $existing_mimes ) {

        $existing_mimes['ttf'] = 'application/x-font-ttf';
        $existing_mimes['woff'] = 'application/x-font-woff';
        $existing_mimes['woff2'] = 'application/x-font-woff2';
        $existing_mimes['otf'] = 'application/x-font-otf';
        return $existing_mimes;
    }

    public static function font_group( $font_groups ){
        $font_group_key = self::$font_group_key;
        $new_group[$font_group_key] = __( 'Custom Fonts - UltraAddons', 'ultraaddons' );
        $font_groups                   = $new_group + $font_groups;
        return $font_groups;
    }
    
    public static function additional_fonts( $fonts ){
        
        $ua_fonts = Fonts::get_fonts();
        
        if( empty( $ua_fonts ) ) return $fonts;
        
        // foreach( $ua_fonts as $font_name => $val ){
        //     $fonts[$font_name] = self::get_font_group();
        // }

        return array_merge( $fonts, $ua_fonts );
    }

    public static function get_font_group(){
        return Fonts::get_term_name();
    }
    
    
    
}