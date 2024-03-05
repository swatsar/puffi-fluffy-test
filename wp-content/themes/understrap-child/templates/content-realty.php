<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<h1 class="entry-header">
		<?php
		the_title();
		?>
	</h1>
    <div class="row">
        <div class="col-8">
            <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
            <?php understrap_link_pages(); ?>
        </div>
        <div class="col-4">
            <div class="entry-content d-flex flex-column">
                <?php
                    foreach (get_realty_meta(get_the_ID()) as $field_name => $field_val){
                        ?>
                <div class="p-2"><b><?=$field_name?>:</b> <?=$field_val?></div>
                        <?php
                    }
                ?>
            </div><!-- .entry-content -->
        </div>
    </div>
	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
