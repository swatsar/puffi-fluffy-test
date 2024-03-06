<?php
// Создаем таксономию "Тип недвижимости"
function create_property_type_taxonomy() {
    $labels = array(
        'name'                       => __('Тип недвижимости'),
        'singular_name'              => __('Тип недвижимости'),
        'search_items'               => __('Искать типы недвижимости'),
        'all_items'                  => __('Все типы недвижимости'),
        'edit_item'                  => __('Редактировать тип недвижимости'),
        'update_item'                => __('Обновить тип недвижимости'),
        'add_new_item'               => __('Добавить новый тип недвижимости'),
        'new_item_name'              => __('Новое имя типа недвижимости'),
        'menu_name'                  => __('Тип недвижимости'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );

    register_taxonomy( 'property_type', array( 'property' ), $args );
}
add_action( 'init', 'create_property_type_taxonomy' );
?>
