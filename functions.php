<?php

// Infinite Ajax Post Scroll
add_action( 'wp_enqueue_scripts', 'my_enqueue' );
function my_enqueue( ) {
   wp_enqueue_script(
      'ajax-script',
      get_template_directory_uri() . '/js/loadPostAjax.js',
      array( 'jquery' ),
      '0.0.2',
      true
   );
   wp_localize_script(
      'ajax-script',
      'load_post_obj',
      array(
		 'ajax_url' => admin_url( 'admin-ajax.php' ),
      )
   );
}

add_action( 'wp_ajax_nopriv_load_previous_post', 'previous_post_ajax_handler' );
add_action( 'wp_ajax_load_previous_post', 'previous_post_ajax_handler' );

function previous_post_ajax_handler() {
   $current_post_id = (int)$_POST['id'];
   $previous_post_id = get_previous_post_id($current_post_id);
   if ($previous_post_id == 0)
   		wp_die();
   $previous_article = get_previous_article($previous_post_id);
   echo $previous_article;
   wp_die();
}

function get_previous_post_id( $post_id ) {
    global $post;

    $oldGlobal = $post;

    $post = get_post( $post_id );

	$previous_post = get_previous_post();

    $post = $oldGlobal;

    if ( '' == $previous_post ) {
        return 0;
    }

    return $previous_post->ID;
}


function get_previous_article ($post_id) {
	global $post;

    $oldGlobal = $post;

    $post = get_post( $post_id );

	ob_start();

	get_template_part( 'template-parts/content/content',
						get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' )
					 );

	$article = ob_get_contents();

	ob_end_clean();

	$post = $oldGlobal;

	return $article;
}
