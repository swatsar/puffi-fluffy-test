<?php

function child_enqueue_styles() {
	wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri() . '/style.css', array(), 100 );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles' );
////
// Отключаем JQM
function remove_jqmigrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];

        // Проверяем, есть ли у скрипта версия с JQM
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'remove_jqmigrate');
//
wp_enqueue_script('jquery');
///
// Форма обратной связи
function custom_feedback_form() {
    ob_start(); ?>
   
   <form id="feedback-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    <input type="hidden" name="action" value="handle_feedback_form">
    <?php wp_nonce_field( 'feedback_form_action', 'feedback_form_nonce' ); ?>
	   
	  
     <div class="header-form">
				 <h3>ФОРМА БЕЗ ПЛАГИНА</h3>
		
		</div>
    <div class="form-row">
        <div class="form-group">
            <label for="name">Имя</label><br>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Почта</label><br>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Телефон</label><br>
            <input type="tel" id="phone" name="phone" pattern="^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="message">Сообщение</label><br>
            <textarea id="message" name="message" required></textarea>
        </div>
    </div>
    <div class="form-row">
        <input type="submit" value="Отправить">
    </div>
</form>


    <?php
    return ob_get_clean();
}

// Добавление JavaScript для обработки AJAX запросов и маски для поля телефона
function custom_feedback_form_script() {
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#phone').mask('+7(999)999-99-99');
            
            $('#feedback-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);

                // Валидация номера телефона
                var phoneInput = $('#phone');
                var phonePattern = /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/;
                if (!phonePattern.test(phoneInput.val())) {
                    alert('Пожалуйста, введите корректный номер телефона!');
                    return;
                }

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            alert(response.data);
                            location.reload();
                        } else {
                            alert(response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Произошла ошибка при обработке запроса. Пожалуйста, попробуйте снова.');
                    }
                });
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_feedback_form_script');

// Добавляем обработчик действия
add_action( 'admin_post_handle_feedback_form', 'handle_feedback_form_submission' );
add_action( 'admin_post_nopriv_handle_feedback_form', 'handle_feedback_form_submission' );

// Обработка данных формы и сохранение в базу данных
function handle_feedback_form_submission() {
    if ( isset( $_POST['name'], $_POST['email'], $_POST['message'], $_POST['phone'] ) && wp_verify_nonce( $_POST['feedback_form_nonce'], 'feedback_form_action' ) ) {
        $name = sanitize_text_field( $_POST['name'] );
        $email = sanitize_email( $_POST['email'] );
        $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
        $message = sanitize_textarea_field( $_POST['message'] );

        $post_data = array(
            'post_title'    => 'Feedback from ' . $name,
            'post_content'  => $message,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'feedback',
            'meta_input'    => array(
                'name'      => $name,
                'email'     => $email,
                'phone'     => $phone,
            ),
        );

        $post_id = wp_insert_post( $post_data );

        if ( $post_id ) {
            wp_send_json_success( 'Спасибо за ваш отклик!' );
        } else {
            wp_send_json_error( 'Произошла ошибка при сохранении отклика. Пожалуйста, попробуйте снова.' );
        }
    } else {
        wp_send_json_error( 'Произошла ошибка валидации данных. Пожалуйста, проверьте заполнение формы.' );
    }
}

// Добавление страницы в административное меню для просмотра данных
function custom_feedback_menu() {
    add_menu_page(
        'Отклики',
        'Отклики',
        'manage_options',
        'feedback-list',
        'custom_feedback_page',
        'dashicons-testimonial',
        20
    );
}
add_action('admin_menu', 'custom_feedback_menu');

// Разработка шаблона для страницы административной части
function custom_feedback_page() {
    ?>
    <div class="wrap">
        <h2>Отклики</h2>
        <?php feedback_form_messages(); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Почта</th>
                    <th>Телефон</th>
                    <th>Сообщение</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $feedback_posts = get_posts(array('post_type' => 'feedback'));
                foreach ($feedback_posts as $post) {
                    setup_postdata($post);
                    ?>
                    <tr>
                        <td><?php echo $post->ID; ?></td>
                        <td><?php echo esc_html( get_post_meta($post->ID, 'name', true) ); ?></td>
                        <td><?php echo esc_html( get_post_meta($post->ID, 'email', true) ); ?></td>
                        <td><?php echo esc_html( get_post_meta($post->ID, 'phone', true) ); ?></td>
                        <td><?php echo esc_html( $post->post_content ); ?></td>
                        <td><?php echo esc_html( $post->post_date ); ?></td>
                        <td><a href="<?php echo esc_url( add_query_arg(array('action' => 'delete_feedback', 'feedback_id' => $post->ID), admin_url('admin.php?page=feedback-list')) ); ?>">Удалить</a></td>
                    </tr>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Удаление записей обратной связи
function custom_handle_feedback_deletion() {
    if ( isset( $_GET['action'], $_GET['feedback_id'] ) && $_GET['action'] === 'delete_feedback' ) {
        $feedback_id = intval( $_GET['feedback_id'] );
        $result = wp_delete_post( $feedback_id, true );
        if ($result) {
            wp_redirect( admin_url('admin.php?page=feedback-list&deleted=true') );
        } else {
            wp_redirect( admin_url('admin.php?page=feedback-list&deleted=false') );
        }
        exit;
    }
}
add_action('admin_init', 'custom_handle_feedback_deletion');

// Добавление сообщений при успешной отправке и при ошибке
function feedback_form_messages() {
    if ( isset( $_GET['sent'] ) ) {
        if ( $_GET['sent'] === 'true' ) {
            echo '<div class="updated"><p>Спасибо за ваш отклик!</p></div>';
        } elseif ( $_GET['sent'] === 'false' ) {
            echo '<div class="error"><p>Произошла ошибка при отправке отклика. Пожалуйста, попробуйте снова.</p></div>';
        }
    }
    if ( isset( $_GET['deleted'] ) ) {
        if ( $_GET['deleted'] === 'true' ) {
            echo '<div class="updated"><p>Запись обратной связи успешно удалена!</p></div>';
        } elseif ( $_GET['deleted'] === 'false' ) {
            echo '<div class="error"><p>Произошла ошибка при удалении записи обратной связи.</p></div>';
        }
    }
}

// Добавляем форму в виде шорткода
add_shortcode( 'feedback_form', 'custom_feedback_form' );
//




//Привет разрабам

function print_to_console() {
    ?>
    <script>
        
        function consoleLogWithStyle(text, style) {
            console.log("%c" + text, style);
        }

        
        consoleLogWithStyle("Привет, разработчики! 🚀 Давайте вместе создавать удивительные вещи! ✨", " text-decoration: underline;", "color: white; background-color: #007bff; font-size: 16px; padding: 5px; border-radius: 5px;");

        
        console.log("%chttps://t.me/TimeBronx", "color: black; text-decoration: underline;");

        
        console.groupCollapsed("Дополнительная информация:");
        console.log("Текущая дата и время:", new Date().toLocaleString());
        console.log("Браузер пользователя:", '<?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8'); ?>');
        console.log("Разрешение экрана:", { width: window.innerWidth, height: window.innerHeight });
        console.log("IP-адрес пользователя:", '<?= htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8'); ?>');
        
        <?php
        
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $url = "http://ip-api.com/json/{$ip_address}";
        $response = @file_get_contents($url);
        if ($response !== false) {
            $data = json_decode($response, true);
            if ($data['status'] == 'success') {
                
                $city = htmlspecialchars($data['city'], ENT_QUOTES, 'UTF-8');
                echo "console.log('%cГород пользователя: " . $city . "', 'font-weight: bold;');";
            } else {
                
                echo "console.log('%cГород пользователя не найден', 'font-weight: bold;');";
            }
        } else {
            
            echo "console.log('%cНе удалось получить данные о местоположении пользователя', 'font-weight: bold;');";
        }
        ?>
        console.groupEnd();
    </script>
    <?php
}

add_action('wp_footer', 'print_to_console');
