<?php
// Функция для добавления HTML-кода выбора типа недвижимости
function get_property_type_select_options() {
    $options_html = '';
    $property_types = get_terms(array(
        'taxonomy' => 'property_type',
        'hide_empty' => false,
    ));
    foreach ($property_types as $type) {
        $options_html .= '<option value="' . $type->name . '">' . $type->name . '</option>';
    }
    return $options_html;
}

// Функция для добавления HTML-кода выбора города
function get_city_select_options() {
    $options_html = '';
    $cities = get_posts(array(
        'post_type' => 'city',
        'posts_per_page' => -1,
    ));
    foreach ($cities as $city) {
        $options_html .= '<option value="' . $city->ID . '">' . $city->post_title . '</option>';
    }
    return $options_html;
}

// Обработчик формы для сохранения выбранного города и типа недвижимости
function property_add_form_handler() {
    // Проверяем данные
    $area = isset($_POST['area']) ? floatval($_POST['area']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : '';
    $address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
    $living_area = isset($_POST['living_area']) ? sanitize_text_field($_POST['living_area']) : '';
    $floor = isset($_POST['floor']) ? sanitize_text_field($_POST['floor']) : '';
    $city_id = isset($_POST['city_id']) ? intval($_POST['city_id']) : '';
    $property_type = isset($_POST['property_type']) ? sanitize_text_field($_POST['property_type']) : '';

    // Получаем данные из метабоксов
    $city_name = isset($_POST['city_id']) ? get_the_title($_POST['city_id']) : '';

    // Проверка валидности данных
    if (empty($area) || empty($price) || empty($address) || empty($city_id) || empty($property_type)) {
        echo 'error';
        wp_die();
    }

    // Заголовок будущей записи
    $post_title = $property_type . ' - ' . $city_name . ' - ' . $area . ' м²'; // Пример формирования заголовка

    // Загрузка изображения
    $property_image = upload_image($_FILES['property_image']);
    if (is_wp_error($property_image)) {
        echo 'error';
        wp_die();
    }

    // Создаем новый объект недвижимости
    $property_args = array(
        'post_title'    => $post_title, // Используем сформированный заголовок
        'post_type'     => 'property',
        'post_status'   => 'publish'
    );

    $property_id = wp_insert_post($property_args);

    // Сохраняем данные формы в метаполя объекта недвижимости
    if ($property_id) {
        update_post_meta($property_id, 'area', $area);
        update_post_meta($property_id, 'price', $price);
        update_post_meta($property_id, 'address', $address);
        update_post_meta($property_id, 'living_area', $living_area);
        update_post_meta($property_id, 'floor', $floor);
        update_post_meta($property_id, 'property_image', $property_image);
        update_post_meta($property_id, 'city_id', $city_id);
        update_post_meta($property_id, 'property_type', $property_type);

        // Возвращаем успешный ответ
        echo 'success';
    } else {
        // Если что-то пошло не так, возвращаем ошибку
        echo 'error';
    }

    // Обязательно завершаем выполнение скрипта
    wp_die();
}

// Добавляем обработчик формы
add_action('admin_post_property_add_form', 'property_add_form_handler');

// Функция для добавления формы
function property_add_form_shortcode() {
    ob_start(); ?>

    <div id="property-add-form-wrapper">
        <form id="property-add-form" class="needs-validation" novalidate enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>" method="post">
            <input type="hidden" name="action" value="property_add_form">
            <div class="mb-3">
                <label for="area" class="form-label">Площадь:</label>
                <input type="text" class="form-control" id="area" name="area" required>
                <div class="invalid-feedback">Введите площадь объекта.</div>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Стоимость:</label>
                <input type="text" class="form-control" id="price" name="price" required>
                <div class="invalid-feedback">Введите стоимость объекта.</div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Адрес:</label>
                <input type="text" class="form-control" id="address" name="address" required>
                <div class="invalid-feedback">Введите адрес объекта.</div>
            </div>
            <div class="mb-3">
                <label for="living_area" class="form-label">Жилая площадь:</label>
                <input type="text" class="form-control" id="living_area" name="living_area">
            </div>
            <div class="mb-3">
                <label for="floor" class="form-label">Этаж:</label>
                <input type="text" class="form-control" id="floor" name="floor">
            </div>
            <div class="mb-3">
                <label for="city_id" class="form-label">Город:</label>
                <select class="form-control" id="city_id" name="city_id" required>
                    <option value="">Выберите город</option>
                    <?php echo get_city_select_options(); ?>
                </select>
                <div class="invalid-feedback">Выберите город.</div>
            </div>
            <div class="mb-3">
                <label for="property_type" class="form-label">Тип недвижимости:</label>
                <select class="form-control" id="property_type" name="property_type" required>
                    <option value="">Выберите тип недвижимости</option>
                    <?php echo get_property_type_select_options(); ?>
                </select>
                <div class="invalid-feedback">Выберите тип недвижимости.</div>
            </div>
            <div class="mb-3">
                <label for="property_image" class="form-label">Изображение объекта:</label>
                <input type="file" class="form-control" id="property_image" name="property_image" required>
                <div id="property_image_preview" class="mt-2"></div>
                <div class="invalid-feedback">Выберите изображение объекта.</div>
            </div>
            <button type="submit" class="btn btn-primary">Добавить объект недвижимости</button>
        </form>

        <div id="form-messages" class="mt-3"></div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            $('#property-add-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(form[0]);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response === 'success') {
                            $('#form-messages').html('<div class="alert alert-success">Объект недвижимости успешно добавлен!</div>');
                            $('#property-add-form')[0].reset();
                        } else {
                            $('#form-messages').html('<div class="alert alert-danger">Произошла ошибка при добавлении объекта недвижимости.</div>');
                        }
                    }
                });
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

// Добавляем шорткод для вывода формы
add_shortcode('property_add_form', 'property_add_form_shortcode');
?>