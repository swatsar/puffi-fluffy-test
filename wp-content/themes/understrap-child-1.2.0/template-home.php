<?php
/*
Template Name: Главная страница
*/

get_header(); // Подключаем шапку сайта
?>

<main id="main-content" class="site-main" role="main">
    <!-- Секция с последними объектами недвижимости -->
    <section id="latest-properties" class="section-latest-properties py-5">
        <div class="container">
            <h2 class="mb-4">Последние объекты недвижимости</h2>
            <div class="row">
                <?php
                $latest_properties = new WP_Query(array(
                    'post_type' => 'property',
                    'posts_per_page' => 5, // Ограничиваем количество последних объектов
                ));

                if ($latest_properties->have_posts()) :
                    while ($latest_properties->have_posts()) : $latest_properties->the_post();
                ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php the_title(); ?></h5>
                                    <p class="card-text">Площадь: <?php echo get_post_meta(get_the_ID(), 'area', true); ?> кв. м</p>
                                    <p class="card-text">Стоимость: <?php echo get_post_meta(get_the_ID(), 'price', true); ?> руб.</p>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">Подробнее</a>
                                </div>
                            </div>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata(); // Сбрасываем данные запроса после окончания цикла
                else :
                    echo '<p>Нет доступных объектов недвижимости.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Секция с городами -->
    <section id="cities" class="section-cities py-5">
        <div class="container">
            <h2 class="mb-4">Города</h2>
            <div class="row">
                <!-- Вывод списка городов -->
                <?php
                $cities = get_posts(array(
                    'post_type' => 'city',
                    'posts_per_page' => -1,
                ));

                if ($cities) :
                    foreach ($cities as $city) :
                ?>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $city->post_title; ?></h5>
                                    <!-- Вывод последних объектов недвижимости в городе -->
                                    <ul class="list-group list-group-flush">
                                        <?php
                                        $city_properties = new WP_Query(array(
                                            'post_type' => 'property',
                                            'posts_per_page' => 5,
                                            'meta_query' => array(
                                                array(
                                                    'key' => 'city_id',
                                                    'value' => $city->ID,
                                                ),
                                            ),
                                        ));

                                        if ($city_properties->have_posts()) :
                                            while ($city_properties->have_posts()) : $city_properties->the_post();
                                        ?>
                                                <li class="list-group-item">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </li>
                                        <?php
                                            endwhile;
                                            wp_reset_postdata(); // Сбрасываем данные запроса после окончания цикла
                                        else :
                                            echo '<li class="list-group-item">Нет доступных объектов недвижимости в этом городе.</li>';
                                        endif;
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Форма добавления объекта недвижимости -->
    <section id="add-property" class="section-add-property py-5">
        <div class="container">
            <h2 class="mb-4">Добавить объект недвижимости</h2>
            <!-- Форма добавления объекта недвижимости с использованием AJAX -->
            <?php
			echo do_shortcode('[wpforms id="236"]');
			?>
        </div>
    </section>
</main>

<?php get_footer(); // Подключаем подвал сайта ?>
