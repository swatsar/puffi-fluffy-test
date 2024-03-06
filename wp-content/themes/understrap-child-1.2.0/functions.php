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

// Регистрация типа поста "Недвижимость"
function register_custom_post_type() {
    $labels = array(
        'name'               => 'Недвижимость',
        'singular_name'      => 'Недвижимость',
        'menu_name'          => 'Недвижимость',
        'name_admin_bar'     => 'Недвижимость',
        'add_new'            => 'Добавить новую',
        'add_new_item'       => 'Добавить новую недвижимость',
        'new_item'           => 'Новая недвижимость',
        'edit_item'          => 'Редактировать недвижимость',
        'view_item'          => 'Просмотр недвижимости',
        'all_items'          => 'Вся недвижимость',
        'search_items'       => 'Искать недвижимость',
        'not_found'          => 'Нет результатов',
        'not_found_in_trash' => 'В корзине нет недвижимости'
    );
 
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'show_in_rest'        => true, // Для Gutenberg
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'property'),
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
    );
 
    register_post_type('property', $args);
}
add_action('init', 'register_custom_post_type');

// Регистрация таксономии "Тип недвижимости"
function register_property_taxonomy() {
    $labels = array(
        'name'                       => 'Тип недвижимости',
        'singular_name'              => 'Тип недвижимости',
        'menu_name'                  => 'Тип недвижимости',
        'search_items'               => 'Искать типы недвижимости',
        'all_items'                  => 'Все типы недвижимости',
        'parent_item'                => 'Родительский тип недвижимости',
        'parent_item_colon'          => 'Родительский тип недвижимости:',
        'edit_item'                  => 'Редактировать тип недвижимости',
        'update_item'                => 'Обновить тип недвижимости',
        'add_new_item'               => 'Добавить новый тип недвижимости',
        'new_item_name'              => 'Новый тип недвижимости',
        'not_found'                  => 'Тип недвижимости не найден',
        'separate_items_with_commas' => 'Разделяйте типы недвижимости запятыми',
        'add_or_remove_items'        => 'Добавить или удалить тип недвижимости',
        'choose_from_most_used'      => 'Выбрать из наиболее используемых типов недвижимости',
        'popular_items'              => 'Популярные типы недвижимости',
        'back_to_items'              => 'Вернуться к типам недвижимости',
    );
 
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'show_ui'                    => true,
        'show_in_rest'               => true, // Для Gutenberg
        'show_admin_column'          => true,
        'query_var'                  => true,
    );
 
    register_taxonomy('property_type', 'property', $args);
}
add_action('init', 'register_property_taxonomy');

// Добавляем произвольные поля для типа поста "Недвижимость"
function add_custom_fields() {
    add_meta_box('property_details', 'Детали недвижимости', 'property_details_callback', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_custom_fields');

function property_details_callback($post) {
    wp_nonce_field(basename(__FILE__), 'property_nonce');
    
    $area = get_post_meta($post->ID, 'area', true);
    $cost = get_post_meta($post->ID, 'cost', true);
    $address = get_post_meta($post->ID, 'address', true);
    $living_area = get_post_meta($post->ID, 'living_area', true);
    $floor = get_post_meta($post->ID, 'floor', true);
   /* $selected_city = get_post_meta($post->ID, 'selected_city', true); */
    
    echo '<label for="area">Площадь:</label>';
    echo '<input type="text" id="area" name="area" value="' . esc_attr($area) . '" /><br>';
    
    echo '<label for="cost">Стоимость:</label>';
    echo '<input type="text" id="cost" name="cost" value="' . esc_attr($cost) . '" /><br>';
    
    echo '<label for="address">Адрес:</label>';
    echo '<input type="text" id="address" name="address" value="' . esc_attr($address) . '" /><br>';
    
    echo '<label for="living_area">Жилая площадь:</label>';
    echo '<input type="text" id="living_area" name="living_area" value="' . esc_attr($living_area) . '" /><br>';
    
    echo '<label for="floor">Этаж:</label>';
    echo '<input type="text" id="floor" name="floor" value="' . esc_attr($floor) . '" /><br>';
    
    // Добавляем поле выбора города
   /* $cities = get_posts(array('post_type' => 'city', 'posts_per_page' => -1));
    echo '<label for="selected_city">Выберите город:</label>';
    echo '<select id="selected_city" name="selected_city">';
    echo '<option value="">Выберите город</option>';
    foreach ($cities as $city) {
        echo '<option value="' . $city->ID . '" ' . selected($selected_city, $city->ID, false) . '>' . $city->post_title . '</option>';
    }
    echo '</select><br>';
    */
    // Добавляем поле для загрузки фотографии
    echo '<label for="property_image">Загрузить фотографию:</label>';
    echo '<input type="file" id="property_image" name="property_image" /><br>';
}

// Сохраняем значения произвольных полей
function save_custom_fields($post_id) {
    if (!isset($_POST['property_nonce']) || !wp_verify_nonce($_POST['property_nonce'], basename(__FILE__))) {
        return $post_id;
    }
 
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
 
    $fields = array('area', 'cost', 'address', 'living_area', 'floor', 'selected_city');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Загружаем изображение и устанавливаем его как изображение записи (featured image)
    if (!empty($_FILES['property_image']['name'])) {
        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($_FILES['property_image'], $upload_overrides);

        if (!empty($uploaded_file['url'])) {
            $attachment = array(
                'post_mime_type' => $_FILES['property_image']['type'],
                'post_title' => sanitize_file_name($_FILES['property_image']['name']),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $uploaded_file['file'], $post_id);
            if (!is_wp_error($attach_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_file['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                set_post_thumbnail($post_id, $attach_id);
            }
        }
    }
}
add_action('save_post', 'save_custom_fields');

// Регистрация типа поста "База Городов"
function register_city_post_type() {
    $labels = array(
        'name'               => 'База Городов',
        'singular_name'      => 'Город',
        'menu_name'          => 'База Городов',
        'name_admin_bar'     => 'Город',
        'add_new'            => 'Добавить новый',
        'add_new_item'       => 'Добавить новый город',
        'new_item'           => 'Новый город',
        'edit_item'          => 'Редактировать город',
        'view_item'          => 'Просмотр города',
        'all_items'          => 'Все города',
        'search_items'       => 'Искать города',
        'not_found'          => 'Города не найдены',
        'not_found_in_trash' => 'В корзине нет городов'
    );
 
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'show_in_rest'        => true, // Для Gutenberg
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'city'),
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
    );
 
    register_post_type('city', $args);
}
add_action('init', 'register_city_post_type');

// Добавляем произвольное поле для выбора города к типу поста "Недвижимость"
function add_city_field() {
    add_meta_box('city_select', 'Выбор города', 'city_select_callback', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_city_field');

function city_select_callback($post) {
    wp_nonce_field(basename(__FILE__), 'city_nonce');
    
    $selected_city = get_post_meta($post->ID, 'selected_city', true);
    $cities = get_posts(array('post_type' => 'city', 'posts_per_page' => -1));
    
    echo '<label for="selected_city">Выберите город:</label>';
    echo '<select id="selected_city" name="selected_city">';
    echo '<option value="">Выберите город</option>';
    foreach ($cities as $city) {
        echo '<option value="' . $city->ID . '" ' . selected($selected_city, $city->ID, false) . '>' . $city->post_title . '</option>';
    }
    echo '</select>';
}

function save_city_field($post_id) {
    if (!isset($_POST['city_nonce']) || !wp_verify_nonce($_POST['city_nonce'], basename(__FILE__))) {
        return $post_id;
    }
 
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
 
    if (isset($_POST['selected_city'])) {
        update_post_meta($post_id, 'selected_city', sanitize_text_field($_POST['selected_city']));
    }
}
add_action('save_post', 'save_city_field');

// Связываем типы постов "Недвижимость" и "База Городов"
function link_property_to_city($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
 
    if (is_post_type_archive('city') || is_tax('property_type')) {
        $query->set('post_type', array('property', 'city'));
    }
}
add_action('pre_get_posts', 'link_property_to_city');
