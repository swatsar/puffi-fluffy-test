<?php
function get_realty_meta($post_id){
    $meta_fiels = ['square'=>'Площадь', 'price'=>'Стоимость', 'address'=>'Адрес',
                'living_space'=>'Жилая площадь', 'floor'=>'Этаж'];
    $result_arr = [];
    foreach ($meta_fiels as $meta_key => $field_name){
        $meta = get_post_meta($post_id,$meta_key,true);
        if($meta){
            $result_arr[$field_name] = $meta;
        }
    }
    return $result_arr;
}
function get_realty_html(){
    ob_start();
    ?>
    <div class="cities_content">
        <h2>Недвижимость</h2>
        <div class="d-flex align-content-around flex-wrap">
    <?php
    $query = new WP_Query( [ 'post_type' => 'realty',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order'   => 'DESC',] );
    while ( $query->have_posts() ) {
        $query->the_post();
        get_template_part( 'templates/part','realty');
    }
    wp_reset_postdata();
    ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}