<?php
get_header(); // Подключаем шапку сайта

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        // Получаем значения произвольных полей
        $area = get_post_meta( get_the_ID(), 'area', true );
        $price = get_post_meta( get_the_ID(), 'price', true );
        $address = get_post_meta( get_the_ID(), 'address', true );
        $living_area = get_post_meta( get_the_ID(), 'living_area', true );
        $floor = get_post_meta( get_the_ID(), 'floor', true );
        $property_image = get_post_meta( get_the_ID(), 'property_image', true );
        $city_id = get_post_meta( get_the_ID(), 'city_id', true );

        // Получаем название города
        $city_name = '';
        if ( $city_id ) {
            $city = get_post( $city_id );
            $city_name = $city ? $city->post_title : '';
        }
        ?>

        <div id="property-<?php the_ID(); ?>" <?php post_class('property-details container py-5 border border-primary rounded'); ?>>
            <div class="row">
                <div class="col-lg-6">
                    <div class="property-images mb-4">
                        <?php if ( ! empty( $property_image ) ) : ?>
                            <img src="<?php echo esc_url( $property_image ); ?>" alt="Property Image" class="property-image img-fluid rounded shadow">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="property-info">
                        <h1 class="property-title mb-4"><?php the_title(); ?></h1>
                        <div class="property-meta">
                            <p class="fs-5"><strong>Площадь:</strong> <?php echo $area; ?></p>
                            <p class="fs-5"><strong>Стоимость:</strong> <?php echo $price; ?></p>
                            <p class="fs-5"><strong>Адрес:</strong> <?php echo $address; ?></p>
                            <p class="fs-5"><strong>Жилая площадь:</strong> <?php echo $living_area; ?></p>
                            <p class="fs-5"><strong>Этаж:</strong> <?php echo $floor; ?></p>
                            <p class="fs-5"><strong>Город:</strong> <?php echo $city_name; ?></p>
                        </div>
                        <div class="property-description mt-4">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile;
endif;

get_footer(); // Подключаем подвал сайта
?>
