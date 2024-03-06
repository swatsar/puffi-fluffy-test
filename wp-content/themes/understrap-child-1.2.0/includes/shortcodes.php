<?php
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
                <label for="property_image" class="form-label">Изображение объекта:</label>
                <input type="file" class="form-control" id="property_image" name="property_image" required>
                <div id="property_image_preview" class="mt-2"></div>
                <div class="invalid-feedback">Выберите изображение объекта.</div>
            </div>
            <button type="submit" class="btn btn-primary">Добавить объект недвижимости</button>
        </form>

        <div id="form-messages" class="mt-3"></div>
    </div>

    <?php
    return ob_get_clean();
}

// Добавляем шорткод для вывода формы
add_shortcode('property_add_form', 'property_add_form_shortcode');
?>
