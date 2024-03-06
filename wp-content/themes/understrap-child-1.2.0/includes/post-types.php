<?php
// Создаем пользовательский тип записи "Недвижимость"
function create_property_post_type() {
    $labels = array(
        'name'               => __('Недвижимость'),
        'singular_name'      => __('Объект недвижимости'),
        'menu_name'          => __('Недвижимость'),
        'name_admin_bar'     => __('Объект недвижимости'),
        'add_new'            => __('Добавить новый'),
        'add_new_item'       => __('Добавить новый объект недвижимости'),
        'new_item'           => __('Новый объект недвижимости'),
        'edit_item'          => __('Редактировать объект недвижимости'),
        'view_item'          => __('Посмотреть объект недвижимости'),
        'all_items'          => __('Вся недвижимость'),
        'search_items'       => __('Искать объект недвижимости'),
        'parent_item_colon'  => __('Родительский объект недвижимости:'),
        'not_found'          => __('Объекты недвижимости не найдены.'),
        'not_found_in_trash' => __('В корзине объекты недвижимости не найдены.')
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'property' ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array( 'title', 'editor', 'thumbnail' ), // Добавляем поддержку миниатюр (фотографии)
        'taxonomies'          => array( 'property_type' ) // Добавляем таксономию "Тип недвижимости"
    );

    register_post_type( 'property', $args );
}
add_action( 'init', 'create_property_post_type' );

// Создаем пользовательский тип записи "Города"
function create_cities_post_type() {
    $labels = array(
        'name'               => __('Города'),
        'singular_name'      => __('Город'),
        'menu_name'          => __('Города'),
        'name_admin_bar'     => __('Город'),
        'add_new'            => __('Добавить новый'),
        'add_new_item'       => __('Добавить новый город'),
        'new_item'           => __('Новый город'),
        'edit_item'          => __('Редактировать город'),
        'view_item'          => __('Посмотреть город'),
        'all_items'          => __('Все города'),
        'search_items'       => __('Искать город'),
        'not_found'          => __('Города не найдены.'),
        'not_found_in_trash' => __('В корзине города не найдены.')
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'city' ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array( 'title', 'editor', 'thumbnail' ), // Поддержка миниатюр (фотографии)
    );

    register_post_type( 'city', $args );
}
add_action( 'init', 'create_cities_post_type' );
?>
