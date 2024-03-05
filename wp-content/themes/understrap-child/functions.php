<?php
// Проверка наличия константы ABSPATH, если ее нет, то завершаем выполнение программы
defined( 'ABSPATH' ) || exit;

// Определение версии темы
define( 'CUSTOM_THEME_VERSION', '1.0' );

// Определение путей к инклюдам и URL темы
define( 'CUSTOM_INC', get_stylesheet_directory() . '/inc/' );
define( 'CUSTOM_URL', get_stylesheet_directory_uri());

// Подключение файлов с пользовательскими типами записей, функциями шаблона и функциями AJAX
foreach ( glob( CUSTOM_INC . '*.php' ) as $file ) {
    require_once $file;
}

// Добавление shortcode [show_property] с функцией-обработчиком show_property_function()
add_shortcode( 'show_property', 'show_property_function' );

// Добавление shortcode [add_property_form] с функцией-обработчиком add_property_form_function()
add_shortcode( 'add_property_form', 'add_property_form_function' );

// Добавление действия wp_enqueue_scripts с функцией-обработчиком enqueue_theme_styles()
add_action( 'wp_enqueue_scripts', 'enqueue_theme_styles' );

/**
 * Функция для вывода свойств
 *
 * @return string
 */
function show_property_function() {
    // Возвращает HTML с помощью функции get_property_html()
    return get_property_html();
}

/**
 * Функция для вывода формы добавления свойства
 *
 * @return string
 */
function add_property_form_function() {
    // Захват вывода и подключение шаблона form-add-property.php
    ob_start();
    get_template_part( 'templates/form', 'add-property');
    return ob_get_clean(); // Возвращает захваченный вывод
}

/**
 * Функция для подключения стилей и скриптов
 *
 * @return void
 */
function enqueue_theme_styles() {
    // Подключение скрипта child-theme.js
    wp_enqueue_script( 'child-js',  CUSTOM_URL . '/js/child-theme.js', array(), '1', true );
    // Локализация скрипта child-js, добавление URL админ-панели и nonce
    wp_localize_script( 'child-js', 'child_js', array(
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'custom_ajax_security' ),
    ));
}
