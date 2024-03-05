<?php
defined( 'ABSPATH' ) || exit;

define( 'WP_THEME_VERSION', '2.18.05' );
define( 'WP_INC', get_stylesheet_directory() . '/inc/' );
define( 'WP_URL', get_stylesheet_directory_uri());

require_once( WP_INC . 'custom_post_type.php');
require_once( WP_INC . 'template_functions.php');
require_once( WP_INC . 'ajax_functions.php');

add_shortcode( 'show_realty', 'show_realty_func' );

function show_realty_func(){
    return get_realty_html();
}

add_shortcode( 'add_realty_form', 'add_realty_form_func' );

function add_realty_form_func(){
    ob_start();
    get_template_part( 'templates/form','add-realty');
    return ob_get_clean();
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {

    wp_enqueue_script('child-js',  WP_URL.'/js/child-theme.js',array(),'1',true);
    wp_localize_script('child-js', 'child_js', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce'=> wp_create_nonce('child_ajax_security'),
    ));
}

