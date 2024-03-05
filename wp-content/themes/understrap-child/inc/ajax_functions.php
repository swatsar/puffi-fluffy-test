<?php
add_action( 'wp_ajax_add_realty', 'add_realty' );
add_action( 'wp_ajax_nopriv_add_realty', 'add_realty' );

function add_realty(){
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'child_ajax_security' )) {
        return;
    }
    $formdData = $_POST;
    if(in_array("", $formdData)){
        return wp_send_json(array('status' => 500,'errors' => 'Заполните все поля'));
    }
    $post_title = $formdData['post_title'];
    $realty_type = $formdData['realty_type'];
    unset($formdData['realty_type']);
    unset($formdData['post_title']);
    unset($formdData['_wpnonce']);
    unset($formdData['action']);

    $post_data = [
        'post_title'    => $post_title,
        'post_status'   => 'publish',
        'post_type'     => 'realty',
        'post_author'   => 0,
        'tax_input'      => array( 'realty_type' => array( $realty_type ) ),
        'meta_input'    => $formdData,
    ];
    $post_id = wp_insert_post(  wp_slash( $post_data ) );

    if(isset($_FILES['image'])){
        $file_return = wp_handle_upload( $_FILES['image'], array('test_form' => false ) );
        if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
            return wp_send_json(array('status' => 400,'errors' => ''));
        } else {
            $filename = $file_return['file'];
            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $file_return['url']
            );
            $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
            wp_update_attachment_metadata( $attachment_id, $attachment_data );
            if( 0 < intval( $attachment_id ) ) {
                set_post_thumbnail( $post_id, $attachment_id );
            }
        }
    }
    if($post_id){
        ob_start();
        $wp_query = new WP_Query(['post_type' => 'realty','page_id'=>$post_id]);
        if ($wp_query->have_posts()) {
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                get_template_part( 'templates/part','realty');
            }
            wp_reset_postdata();
        }
        $content = ob_get_clean();
        return wp_send_json(array('status' => 200,'content' => $content));
    }

}