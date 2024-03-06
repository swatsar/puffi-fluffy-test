<?php
// Добавляем произвольные поля и метабокс
function add_property_meta_boxes() {
    add_meta_box(
        'property_details',
        __('Детали недвижимости'),
        'render_property_details_meta_box',
        'property',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_property_meta_boxes' );

// Функция отрисовки метабокса для деталей недвижимости
function render_property_details_meta_box( $post ) {
    // Добавляем поля для ввода информации о недвижимости
    $fields = array(
        'area'          => __('Площадь:'),
        'price'         => __('Стоимость:'),
        'address'       => __('Адрес:'),
        'living_area'   => __('Жилая площадь:'),
        'floor'         => __('Этаж:')
    );

    foreach ($fields as $field_key => $field_label) {
        $field_value = get_post_meta($post->ID, $field_key, true);
        echo '<label for="' . $field_key . '">' . $field_label . '</label>';
        echo '<input type="text" id="' . $field_key . '" name="' . $field_key . '" value="' . esc_attr($field_value) . '" /><br />';
    }

    // Добавляем кнопку для выбора изображений
    $property_image = get_post_meta($post->ID, 'property_image', true);
    echo '<label for="property_image">' . __('Изображение объекта:') . '</label>';
    echo '<input type="text" id="property_image" name="property_image" value="' . esc_attr($property_image) . '" />';
    echo '<input type="button" id="property_image_button" class="button" value="' . __('Выбрать изображение') . '" /><br />';
    echo '<div id="property_image_preview">';
    if (!empty($property_image)) {
        echo '<img src="' . esc_url($property_image) . '" style="max-width: 200px;" />';
    }
    echo '</div>';
}

// Обработчик сохранения данных метабокса
function save_property_details_meta_box( $post_id ) {
    // Проверка прав доступа
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Сохраняем значения произвольных полей
    $fields = array( 'area', 'price', 'address', 'living_area', 'floor', 'property_image' );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }

    // Сохраняем значение произвольного поля для выбора города
    if ( isset( $_POST['city_id'] ) ) {
        update_post_meta( $post_id, 'city_id', intval( $_POST['city_id'] ) );
    }
}
add_action( 'save_post', 'save_property_details_meta_box' );

// Добавляем скрипт для выбора изображений
function property_image_script() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#property_image_button').click(function() {
                var custom_uploader = wp.media({
                    title: 'Выберите изображение',
                    button: {
                        text: 'Выбрать'
                    },
                    multiple: false
                });
                custom_uploader.on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#property_image').val(attachment.url);
                    $('#property_image_preview').html('<img src="' + attachment.url + '" style="max-width: 200px;" />');
                });
                custom_uploader.open();
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'property_image_script');
?>
