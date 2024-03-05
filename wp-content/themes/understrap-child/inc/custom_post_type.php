<?php
add_action( 'init', 'register_post_types' );
function register_post_types(){
    register_post_type( 'realty', [
        'label'  => null,
        'labels' => [
            'name'               => 'Недвижимость',
            'singular_name'      => 'Недвижимость',
            'add_new'            => 'Добавить Недвижимость',
            'add_new_item'       => 'Добавление Недвижимости',
            'edit_item'          => 'Редактирование Недвижимости',
            'new_item'           => 'Новая Недвижимость',
            'view_item'          => 'Смотреть Недвижимость',
            'search_items'       => 'Искать Недвижимость',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Недвижимость',
        ],
        'description'            => '',
        'public'                 => true,
        'show_in_menu'           => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => null,
        'hierarchical'        => false,
        'supports'            => [ 'title', 'editor','thumbnail' ],
        'taxonomies'          => ['realty_type'],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ] );

    register_taxonomy( 'realty_type', [ 'realty' ], [
        'label'                 => '',
        'labels'                => [
            'name'              => 'Тип недвижимости',
            'singular_name'     => 'Тип недвижимости',
            'search_items'      => 'Найти тип недвижемости',
            'all_items'         => 'Все типы недвижемости',
            'view_item '        => 'Посмотреть тип недвижемости',
            'parent_item'       => 'Родительский тип',
            'parent_item_colon' => 'Родительский тип:',
            'edit_item'         => 'Редактиовать тип',
            'update_item'       => 'Обновить тип',
            'add_new_item'      => 'AДобавить новый тип',
            'new_item_name'     => 'Новый тип',
            'menu_name'         => 'Тип недвижемости',
            'back_to_items'     => '← Назад',
        ],
        'description'           => '',
        'public'                => true,
        'hierarchical'          => true,

        'rewrite'               => true,
        'capabilities'          => array(),
        'meta_box_cb'           => null,
        'show_admin_column'     => true,
        'show_in_rest'          => null,
        'rest_base'             => null,
    ] );
}