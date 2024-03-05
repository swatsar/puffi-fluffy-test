<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<h1>
		<?php
		the_title();
		?>
	</h1><!-- .entry-header -->

    <div class="row mt-4">
        <div class="col-8">
            <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
        </div>
        <div class="col-4">
            <div class="entry-content d-flex flex-column">
                <?php
                the_content();
                ?>
            </div>
        </div>
    </div>
    <div class="realty-wrap mt-5">
        <h2>Недвижимость в городе <?=get_the_title()?></h2>
        <div class="d-flex align-content-around flex-wrap">
            <?php
            $query = new WP_Query( [ 'post_type' => 'realty',
                'posts_per_page' => 10,
                'orderby' => 'date',
                'order'   => 'DESC',
                'meta_query' => [
                    [
                        'key' => 'cities',
                        'value' => get_the_ID(),
                    ],
                ] ] );
            while ( $query->have_posts() ) {
                $query->the_post();
                get_template_part( 'templates/part','realty');
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>



	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
