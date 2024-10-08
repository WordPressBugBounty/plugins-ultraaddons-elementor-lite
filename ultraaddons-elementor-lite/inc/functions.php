<?php

use UltraAddons\Extensions\Custom_Fonts as Ex_Fonts; //Ex - Extensions
use UltraAddons\Core\Custom_Fonts_Handle as Fonts;

defined( 'ABSPATH' ) || die();

/**
 * Getting Help your for Widget.
 * 
 * Link's prefix, I mean: first part of URL has taken from constant
 * 
 * @since 1.0.0.2
 * @by Saiful
 * 
 * @param string $class_name
 * @param type $object
 * @return string Full URL link for Class
 */
function ultraaddons_help_url( $class_name, $object = false ){
    
    /**
     * using Constant: ULTRA_ADDONS_WIDGET_HELP_ULR 
     * This constant has come from init.php file inside root directory of this plugin
     * 
     * @since 1.0.0.3
     */
    return ULTRA_ADDONS_WIDGET_HELP_ULR . $class_name;
}

if( ! function_exists( 'ultraaddons_is_cf7_activated' ) ){
    /**
    * Check if contact form 7 is activated
    *
    * @return bool
    */
   function ultraaddons_is_cf7_activated() {
           return class_exists( '\WPCF7' );
   }
}

if( !function_exists( 'ultraaddons_get_cf7_forms' ) ){   
    /**
     * Get a list of all CF7 forms
     *
     * @return array
     */
    function ultraaddons_get_cf7_forms() {
            $forms = [];

            if ( ultraaddons_is_cf7_activated() ) {
                    $_forms = get_posts( [
                            'post_type'      => 'wpcf7_contact_form',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                    ] );

                    if ( ! empty( $_forms ) ) {
                            $forms = wp_list_pluck( $_forms, 'post_title', 'ID' );
                    }
            }

            return $forms;
    }
}

if( !function_exists( 'ultraaddons_get_current_user_display_name' ) ){
    /**
     * Get user name
     * @return type
     */
    function ultraaddons_get_current_user_display_name() {
            $user = wp_get_current_user();
            $name = 'user';
            if ( $user->exists() && $user->display_name ) {
                    $name = $user->display_name;
            }
            return $name;
    }
}
if( ! function_exists( 'ultraaddons_do_shortcode' ) ){
    /**
     * Call a shortcode function by tag name.
     *
     * @since  1.0.0
     *
     * @param string $tag     The shortcode whose function to call.
     * @param array  $atts    The attributes to pass to the shortcode function. Optional.
     * @param array  $content The shortcode's content. Default is null (none).
     *
     * @return string|bool False on failure, the result of the shortcode on success.
     */
    function ultraaddons_do_shortcode( $tag, array $atts = array(), $content = null ) {
            global $shortcode_tags;
            if ( ! isset( $shortcode_tags[ $tag ] ) ) {
                    return false;
            }
            return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }
}

/**
 * Get Elementor instance
 * 
 * It's a Object of Elementor
 * Which will be need for every widget register of Elementor Widget.
 * 
 * @since 1.0.0.2
 * @by Saiful
 *
 * @return \Elementor\Plugin Instance
 */
function ultraaddons_elementor() {
	return \Elementor\Plugin::instance();
}

/**
 * Getting Widgets Array with full args
 * 
 * @return Array list of Widgets
 */
function ultraaddons_get_widgets(){
    return \UltraAddons\Core\Widgets_Manager::widgets();
}

/**
 * Get Boolean for Pro
 * 
 * @return bool True|False
 */
function ultraaddons_is_pro(){
//    return false; //Only for Development Perspose.
    return defined( 'ULTRA_ADDONS_PRO_VERSION' );
}

/**
 * Get Plugin's Version name.
 * For Premium, it will return pro,
 * and for free, it will return free
 * 
 * @return string free|pro
 */
function ultraaddons_plugin_version(){
    return ultraaddons_is_pro() ? 'pro' :'free';
}

/**
 * Outpur elementor page content to any where
 * Just need that template id
 * Mean: Post ID of that template
 * 
 * @param int $post_id POST Id, can be any post id. basically for Elementor Template's POSD id
 * @return boolean|String|null if not found, return false. if not set post id, return null and for success return content
 */
function ultraaddons_elementor_display_content( $post_id = false ){
    if( empty( $post_id ) || ! $post_id || ! is_numeric( $post_id ) ){
        return;
    }
    
    (int) $select_post_id = $post_id;
    if ( \Elementor\Plugin::instance()->documents->get( $select_post_id )->is_built_with_elementor() ) {
        $final_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
        if( ! empty( $final_content ) ){
            return $final_content;
        }else{
            $title = get_the_title( $select_post_id );
            $custom_content = '<p class="ultraaddons-header-footer-empty">';
            $custom_content .= "Your Header/Footer template [$title] is empty.";
            $custom_content .= '</p>';
            return apply_filters( 'ultraaddons_template_empty_output', $custom_content );
        }
        
    }
    return false;
}

function ultraaddons_icon_markup( $size = 'small' ){
    $markup = "<i class='ultraaddons ua_icon ua_icon_{$size}'></i>";
    return apply_filters( 'ultraaddons_icon_murkup', $markup );
}


function ultraaddons_get_placeholder_image_src( $image = '' ) {
        $placeholder_image = ULTRA_ADDONS_ASSETS . 'images/no-image.png';

        /**
         * Get placeholder image source.
         * 
         *
         * Filters the source of the default placeholder image used by Elementor.
         *
         * @since 1.0.0
         *
         * @param string $placeholder_image The source of the default placeholder image.
         */
        $placeholder_image = apply_filters( 'ultraaddons_get_placeholder_image_src', $placeholder_image );

        return $placeholder_image;
}

/**
* Checks a control value for being empty, including a string of '0' not covered by PHP's empty().
*
* @param mixed $source
* @param bool|string $key
*
* @return bool
*/
function ultraaddons_widget_data_is_empty( $source, $key = false ) {
       if ( is_array( $source ) ) {
               if ( ! isset( $source[ $key ] ) ) {
                       return true;
               }

               $source = $source[ $key ];
       }

       return '0' !== $source && empty( $source );
}

/**
 * Cart Link.
 *
 * Displayed a link to the cart including the number of items present and the cart total.
 *
 * @return void
 */
function ultraaddons_woocommerce_cart_link() {
        if( ! WC()->cart ) return;
        ?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'ultraaddons' ); ?>">
            <?php
            $item_cmount = WC()->cart->get_cart_contents_count();
            /* translators: number of items in the mini cart. */
            $item_count_text = _n( 'item', 'items', $item_cmount, 'ultraaddons' );
            $item_count_text = apply_filters( 'ultraaddons_item_text', $item_count_text, $item_cmount );
            if( $item_cmount > 0 ){
            ?>
            <span class="count">
                <span class="cart-count"><?php echo esc_html( $item_cmount ); ?></span>
                <span class="cart-item-text"><?php echo esc_html( $item_count_text ); ?></span>
            </span>
            <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
            <?php
            }
            ?>
        </a>
        <?php
}
if ( ! function_exists( 'ultraaddons_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function ultraaddons_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		ultraaddons_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ultraaddons_woocommerce_cart_link_fragment' );

/**
 * Finally I will creat field my custom code, currently using CMB2
 */
//include_once __DIR__ . '/wp/custom-field.php';

function ultraaddons_title_tag( $title_tag ){
    $title_tag_array = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6','div', 'span', 'p' );
    if( in_array( $title_tag, $title_tag_array ) ) {
        return $title_tag;
    } else {
        return 'h4';
    }
}

function ultraaddons_parse_text_editor( $content ) {  
    $content = shortcode_unautop( $content );
    $content = do_shortcode( $content );
    $content = wptexturize( $content );
    if ( $GLOBALS['wp_embed'] instanceof \WP_Embed ) {
        $content = $GLOBALS['wp_embed']->autoembed( $content );
    }
    return $content;
}

function ultraaddons_allowed_html_tags( $level = 'basic' ) {
    $allowed_html = [
        'b' => [],
        'i' => [],
        'u' => [],
        'em' => [],
        'br' => [],
        'img' => [
            'src' => [],
            'alt' => [],
            'height' => [],
            'width' => [],
        ],
        'abbr' => [
            'title' => [],
        ],
        'span' => [
            'class' => [],
        ],
        'strong' => [],
    ];

    if ( $level === 'advanced' ) {
        $advanced = [
            'acronym' => [
                'title' => [],
            ],
            'q' => [
                'cite' => [],
            ],
            'img' => [
                'src' => [],
                'alt' => [],
                'height' => [],
                'width' => [],
            ],
            
            'time' => [
                'datetime' => [],
            ],
            'cite' => [
                'title' => [],
            ],
            'a' => [
                'href' => [],
                'title' => [],
                'class' => [],
                'id' => [],
            ],
        ];

        $allowed_html = array_merge( $allowed_html, $advanced);
    }

    return $allowed_html;
}

function ultraaddons_validate_html_tag( $tag ) {
    static $allowed_html_wrapper_tags = [
        'article',
        'aside',
        'div',
        'footer',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'header',
        'main',
        'nav',
        'p',
        'section',
        'span',
    ];

    return in_array( strtolower( $tag ), $allowed_html_wrapper_tags ) ? $tag : 'div';
}

function ultraaddons_addons_kses( $string = '', $level = 'basic' ) {
    return wp_kses( $string, ultraaddons_allowed_html_tags( $level ) );
}

/**
 * Returns all registered post types
 */
function ultraaddons_get_post_types($args = [], $array_diff_key = []){
    $post_type_args = [
        'public' => true,
        'show_in_nav_menus' => true
    ];

    if (!empty($args['post_type'])) {
        $post_type_args['name'] = $args['post_type'];
        unset($args['post_type']);
    }

    $post_type_args = wp_parse_args($post_type_args, $args);
    $_post_types = get_post_types($post_type_args, 'objects');

    $post_types = array(
        'by_id'    => __('Manual Selection', 'ultraaddons'),
        'category' => __('Category', 'ultraaddons'),
    );

    foreach ($_post_types as $post_type => $object) {
        $post_types[$post_type] = $object->label;
    }
    if( !empty( $array_diff_key ) ){
        $post_types = array_diff_key( $post_types, $array_diff_key );
    }
    return $post_types;
}

function ultraaddons_get_grid_metro_size() {
    return [
        '1:1'   => esc_html__( 'Width 1 - Height 1', 'ultraaddon' ),
        '1:2'   => esc_html__( 'Width 1 - Height 2', 'ultraaddon' ),
        '1:0.7' => esc_html__( 'Width 1 - Height 70%', 'ultraaddon' ),
        '1:1.3' => esc_html__( 'Width 1 - Height 130%', 'ultraaddon' ),
        '2:1'   => esc_html__( 'Width 2 - Height 1', 'ultraaddon' ),
        '2:2'   => esc_html__( 'Width 2 - Height 2', 'ultraaddon' ),
    ];
}

function ultraaddons_get_the_post_thumbnail( $args = array() ) {
    if ( ! empty( $args['post_id'] ) ) {
        $args['id'] = get_post_thumbnail_id( $args['post_id'] );
    } else {
        $args['id'] = get_post_thumbnail_id( get_the_ID() );
    }
    return ultraaddons_get_attachment_by_id( $args );
}

function ultraaddons_get_attachment_by_id( $args = array() ) {
    $defaults = array(
        'id'     => '',
        'size'   => 'full',
        'width'  => '',
        'height' => '',
        'crop'   => true,
    );
    $args = wp_parse_args( $args, $defaults );
    $image_full = ultraaddons_get_the_post_thumbnail( $args['id'] );
    if ( $image_full === false ) {
        return false;
    }
    $url           = $image_full['src'];
    $cropped_image = ultraaddons_get_image_cropped_url( $url, $args );
    if ( $cropped_image[0] === '' ) {
        return '';
    }
    $image_attributes = array(
        'src' => $cropped_image[0],
        'alt' => $image_full['alt'],
    );

    if ( isset( $cropped_image[1] ) ) {
        $image_attributes['width'] = $cropped_image[1];
    }

    $image = ultraaddons_build_img_tag( $image_attributes );

    // Wrap img with caption tags.
    if ( isset( $args['caption_enable'] ) && $args['caption_enable'] === true && $image_full['caption'] !== '' ) {
        $before = '<figure>';
        $after  = '<figcaption class="wp-caption-text gallery-caption">' . $image_full['caption'] . '</figcaption></figure>';
        $image = $before . $image . $after;
    }

    return $image;
}

function ultraaddons_build_img_tag( $attributes = array() ) {
    if ( empty( $attributes['src'] ) ) {
        return '';
    }
    $attributes_str = '';
    if ( ! empty( $attributes ) ) {
        foreach ( $attributes as $attribute => $value ) {
            $attributes_str .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
        }
    }
    $image = '<img ' . $attributes_str . ' />';
    return $image;
}

function ultraaddons_get_image_cropped_url( $url, $args = array() ) {
    extract( $args );
    if ( $url === false ) {
        return array( 0 => '' );
    }

    if ( $size === 'full' ) {
        return array( 0 => $url );
    }

    if ( $size !== 'custom' && ! preg_match( '/(\d+)x(\d+)/', $size ) ) {
        $attachment_url = wp_get_attachment_image_url( $args['id'], $size );

        if ( ! $attachment_url ) {
            return array( 0 => $url );
        } else {
            return array( 0 => $attachment_url );
        }
    }

    if ( $size !== 'custom' ) {
        $_sizes = explode( 'x', $size );
        $width  = $_sizes[0];
        $height = $_sizes[1];
    } else {
        if ( $width === '' ) {
            $width = 9999;
        }

        if ( $height === '' ) {
            $height = 9999;
        }
    }

    $width  = (int) $width;
    $height = (int) $height;

    if ( $width === 9999 || $height === 9999 ) {
        $crop = false;
    }

    if ( $width !== '' && $height !== '' && function_exists( 'aq_resize' ) ) {
        $crop_image = aq_resize( $url, $width, $height, $crop, false );         

        if ( is_array( $crop_image ) && $crop_image[0] !== '' ) {
            return $crop_image;
        }
    }

    return array( 0 => $url );
}

function ultraaddons_image_placeholder( $width, $height ) {
    echo '<img src="' . ULTRA_ADDONS_ASSETS . 'images/no-image.png" width="'.$width.'" height="'.$width.'" alt="' . esc_attr__( 'Thumbnail', 'droit-elementor-addons' ) . '"/>';
}

/**
 * Get UltraAddons selected fonts
 * 
 * Font selected from: Dashabord->UltraAddons->Custom Ex_Fonts Menu
 * 
 * @since 1.1.0.5
 */
function ultraaddons_get_fonts(){
    return Fonts::get_fonts();;
}

/**
 * UltraAddons Button Hover
 * 
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 */

function ultraaddons_button_hover(){
    return  array(
		'none'=>'None',
        'hvr-back-pulse' => 'Back Pulse',
        'hvr-sweep-to-right' => 'Sweep To Right',
        'hvr-sweep-to-left' => 'Sweep To Left',
        'hvr-sweep-to-bottom' => 'Sweep To Bottom',
        'hvr-sweep-to-top' => 'Sweep To Top',
        'hvr-sweep-to-top'=>	'Sweep To Top',
        'hvr-bounce-to-top'=>	'Bounce To Top',
        'hvr-bounce-to-right'=>	'Bounce To Right',
        'hvr-bounce-to-left'=>	'Bounce To Left',
        'hvr-bounce-to-bottom'=> 'Bounce To Bottom',
        'hvr-shadow'=> 'Shadow',
        'hvr-grow-shadow'=> 'Grow Shadow',
        'hvr-float-shadow'=> 'Float Shadow',
        'hvr-ripple-out'=> 'Ripple Out',
        'hvr-underline-from-center'=> 'Underline From Center',
        'hvr-overline-from-left'=> 'Overline From Left',
        'hvr-rectangle-in'=> 'Rectangle In',
        'hvr-rectangle-out'=> 'Rectangle Out',
        'hvr-shutter-in-vertical'=> 'Shutter In Vertical',
        'hvr-shutter-out-vertical'=> 'Shutter out Vertical',
        'hvr-shutter-in-horizontal'=> 'Shutter In Horizontal',
        'hvr-shutter-out-horizontal'=> 'Shutter Out Horizontal', 
        'hvr-float'=> 'Float',  
        'hvr-sink'=> 'Sink', 
        'hvr-buzz'=> 'Buzz', 
    );
}

/**
 * UltraAddons Animation
 * 
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 */

function ultraaddons_animation(){
    return  array(
        'fadeIn'            => 'fadeIn',
        'fadeInDown'        => 'fadeInDown',
        'fadeInLeft'        => 'fadeInLeft',
        'fadeInRight'       => 'fadeInRight',
        'fadeInUp'          => 'fadeInUp',
        'bounce'            => 'bounce',
        'bounceIn'          => 'bounceIn',
        'bounceInDown'      => 'bounceInDown',
        'bounceInLeft'      => 'bounceInLeft',
        'bounceInRight'     => 'bounceInRight',
        'bounceInUp'        => 'bounceInUp',
        'flip'              => 'flip',
        'flipInX'           => 'flipInX',
        'flipInY'           => 'flipInY',
        'backInDown'        => 'backInDown',
        'backInLeft'        => 'backInLeft',
        'backInRight'       => 'backInRight',
        'backInUp'          => 'backInUp',
        'rotateIn'          => 'rotateIn',
        'rotateInDownLeft'  => 'rotateInDownLeft',
       'rotateInDownRight'  => 'rotateInDownRight',
        'rotateInUpLeft'    => 'rotateInUpLeft',
       'rotateInUpRight'    => 'rotateInUpRight',

    );
}
/**
 * Special Funcation for enable Theme demo
 * Only for Theme User
 * will not use to any where in our plugin.
 * 
 * *******************************
 * INSTRUCTION
 * *******************************
 * Obviously check by function_exists() in your theme's functions.php file.
 * Example file Available in Elementor Demo Manager plugin
 * @details 
 * 
 * @author Saiful Islam <codersaiful@gmail.com>
 * @since 1.1.0.9
 *
 * @return object
 */
function ultraaddons_theme_demo(){
    return new \UltraAddons\Base\Theme_Demo;
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Adds backwards compatibility for wp_body_open() introduced with WordPress 5.2
	 *
	 * @since 1.1.4.2
	 * @see https://developer.wordpress.org/reference/functions/wp_body_open/
	 * @return void
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * For navigation menu widget replace submenu class name 
 * 
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 *
 * @return object
 */
function ultraaddons_submenu_class($menu) {    
    $menu = preg_replace('/ class="sub-menu"/','/ class="subnav sub-menu" /', $menu);        
    return $menu;
}

add_filter('wp_nav_menu','ultraaddons_submenu_class');

/**
 * Get Formidable Forms List
 * 
 * @author Saiful Islam <codersaiful@gmail.com>
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 * @return array
 */

if( class_exists( 'FrmForm' ) ){

    function ultraaddons_get_formidable_forms(){
    
            $query   = array();
            $where   = apply_filters( 'frm_forms_dropdown', $query, 'form' );
            $forms   = FrmForm::get_published_forms( $where, 999, 'exclude' );
            $options = array( '' => '' );

            foreach ( $forms as $form ) {
                $form_title           = '' === $form->name ? __( '(no title)', 'ultraaddons' ) : FrmAppHelper::truncate( $form->name, 50 );
                $options[ $form->id ] = esc_html( $form_title );
            }
            return $options;

    }
}


/**
 * Get WPForms List
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 * @return array
 */
if( class_exists( 'WPForms\WPForms' ) ){
    function ultraaddons_get_wpform_list(){
    $args = array(
        'post_type' => 'wpforms', 
        'posts_per_page' => -1
    );

        $formlist=[];
        
        if( $post = get_posts($args)){
            $formlist[0] = esc_html__('Select WPforms', 'ultraaddons');
            foreach ( $post as $posts ) {
                (int)$formlist[$posts->ID] = $posts->post_title;
            }
        }
        else{
            (int)$formlist['0'] = esc_html__('No wpforms found!', 'ultraaddons');
        }
    return $formlist;
    }
}
  
/**
 * Get NinjaForms List
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 * @return array
 */
 function ultraaddons_get_ninja_form_list(){
        $options = array();

        if (class_exists('Ninja_Forms')) {
            $contact_forms = Ninja_Forms()->form()->get_forms();

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {

                $options[0] = esc_html__('Select Ninja Form', 'ultraaddons');

                foreach ($contact_forms as $form) {
                    $options[$form->get_id()] = $form->get_setting('title');
                }
            }
        } else {
            $options[0] = esc_html__('No form found. Create a Form First', 'ultraaddons');
        }

        return $options;
    }

/**
 * Get Caldera Forms List
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 * @return array
 */
function ultraaddons_get_caldera_form_list(){
    $options = array();

    if (class_exists('Caldera_Forms')) {
        $contact_forms = \Caldera_Forms_Forms::get_forms(true, true);

        if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
            $options[0] = esc_html__('Select Caldera Form', 'ultraaddons');
            foreach ($contact_forms as $form) {
                $options[$form['ID']] = $form['name'];
            }
        }
    } else {
        $options[0] = esc_html__('Create a Form First', 'ultraaddons');
    }

    return $options;
}


/**
 * Get WeForms List
 * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
 * @since 1.1.0.9
 * @return array
 */

function ultraaddons_get_weform_list(){

    $weforms_list = get_posts(array(
        'post_type' => 'wpuf_contact_form',
        'posts_per_page' => -1
    ));

    $options = array();

    if (!empty($weforms_list) && !is_wp_error($weforms_list)) {
        $options[0] = esc_html__('Select weForm', 'ultraaddons');
        foreach ($weforms_list as $form_list) {
            $options[$form_list->ID] = $form_list->post_title;
        }
    } else {
        $options[0] = esc_html__('Create a Form First', 'ultraaddons');
    }

    return $options;
}

function ultraaddons_optimize_array( $array ){


    if( ! is_array( $array ) ) return $array;

    foreach ($array as $key => &$value) {
        if ( ! is_bool( $value ) && empty($value)) {
           unset($array[$key]);
        }
        else {
           if (is_array($value)) {
              $value = ultraaddons_optimize_array($value);
              if (! is_bool( $value ) && empty($value)) {
                 unset($array[$key]);
              }
           }
        }
     }
  
     return $array;
}