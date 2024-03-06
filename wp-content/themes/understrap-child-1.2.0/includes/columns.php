<?php
// Добавляем столбцы в таблицу "Вся недвижимость"
function add_custom_columns_to_property_list($columns) {
    $columns['area'] = __('Площадь');
    $columns['price'] = __('Стоимость');
    $columns['address'] = __('Адрес');
    $columns['city'] = __('Город');
    $columns['description'] = __('Описание');
    $columns['image'] = __('Изображение');

    return $columns;
}
add_filter('manage_property_posts_columns', 'add_custom_columns_to_property_list');

// Заполняем столбцы данными
function fill_custom_columns_in_property_list($column, $post_id) {
    switch ($column) {
        case 'area':
            echo get_post_meta($post_id, 'area', true);
            break;
        case 'price':
            echo get_post_meta($post_id, 'price', true);
            break;
        case 'address':
            echo get_post_meta($post_id, 'address', true);
            break;
        case 'city':
            $city_id = get_post_meta($post_id, 'city_id', true);
            $city_name = '';
            if ($city_id) {
                $city = get_post($city_id);
                $city_name = $city ? $city->post_title : '';
            }
            echo $city_name;
            break;
        case 'description':
            echo wp_trim_words(get_post_field('post_content', $post_id), 10); // Отображаем первые 10 слов описания
            break;
        case 'image':
            $property_image = get_post_meta($post_id, 'property_image', true);
            echo '<img src="' . esc_url($property_image) . '" style="max-width: 100px;" />';
            break;
        default:
            break;
    }
}
add_action('manage_property_posts_custom_column', 'fill_custom_columns_in_property_list', 10, 2);
?>
