<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );
/**
 * 
 */
// Подключаем файлы с пользовательскими типами записей
require_once get_stylesheet_directory() . '/includes/post-types.php';

// Подключаем файлы с таксономиями
require_once get_stylesheet_directory() . '/includes/taxonomies.php';

// Подключаем файлы с метабоксами и произвольными полями
require_once get_stylesheet_directory() . '/includes/meta-boxes.php';

// Подключаем файлы с добавлением столбцов
require_once get_stylesheet_directory() . '/includes/columns.php';

// Обработка отправленной формы добавления недвижимости
function handle_property_submission( $fields ) {
    // Создаем новую запись недвижимости
    $post_data = array(
        'post_title'    => sanitize_text_field( $fields['type'] ), // Название поста будет типом недвижимости
        'post_content'  => '', // Можно добавить описание, если нужно
        'post_status'   => 'pending', // Предварительно сохраняем как черновик
        'post_type'     => 'property' // Тип записи недвижимости
    );

    // Создаем пост недвижимости
    $post_id = wp_insert_post( $post_data );

    // Сохраняем значения полей из формы как метаполя
    update_post_meta( $post_id, 'area', sanitize_text_field( $fields['area'] ) );
    update_post_meta( $post_id, 'price', sanitize_text_field( $fields['price'] ) );
    update_post_meta( $post_id, 'address', sanitize_text_field( $fields['address'] ) );
    update_post_meta( $post_id, 'living_area', sanitize_text_field( $fields['living_area'] ) );
    update_post_meta( $post_id, 'floor', sanitize_text_field( $fields['floor'] ) );

    // Сохраняем выбранный город
    update_post_meta( $post_id, 'city', sanitize_text_field( $fields['city'] ) );

    // Обработка загруженного изображения
    if ( isset( $fields['image'] ) && ! empty( $fields['image']['name'] ) ) {
        $upload_overrides = array( 'test_form' => false );
        $uploaded_image = wp_handle_upload( $fields['image'], $upload_overrides );
        
        if ( isset( $uploaded_image['url'] ) ) {
            update_post_meta( $post_id, 'property_image', esc_url( $uploaded_image['url'] ) );
        }
    }
}

// Добавляем обработчик для формы WPForms
add_action( 'wpforms_process_complete_236', 'handle_property_submission', 10, 4 ); 


