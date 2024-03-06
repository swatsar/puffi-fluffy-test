<?php
/*
Template Name: Главная страница
*/

get_header(); // Подключаем шапку сайта
?>

<main id="main-content" class="site-main" role="main">
    <!-- Вывод основного контента страницы -->
    <section class="section-main-content py-5">
        <div class="container">
            <?php
                // Выводим основной контент страницы
                while (have_posts()) : the_post();
                    the_content();
                endwhile;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); // Подключаем подвал сайта ?>
