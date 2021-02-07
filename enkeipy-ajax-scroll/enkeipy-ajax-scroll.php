<?php

/**
 * Plugin Name:       Infinite Ajax Post Scroll
 * Plugin URI:        https://github.com/enkeipy/Infinite-Ajax-Post-Scroll
 * Description:       Basic Infinite Ajax Post Scroll for WordPress.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Nikita Kukshynsky
 * Author URI:        https://enkeipy.dev/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version and path to js file
 */
define('NK_INFINITE_AJAX_POST_SCROLL_VERSION', '1.0.0');
define('NK_INFINITE_AJAX_POST_SCROLL_JS_PATH', plugins_url('public/js/main.js', __FILE__));

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */

add_action('wp_enqueue_scripts', 'nk_iaps_enqueue');
add_action('wp_ajax_nopriv_load_previous_post', 'nk_iaps_previous_post_ajax_handler');
add_action('wp_ajax_load_previous_post', 'nk_iaps_previous_post_ajax_handler');

function nk_iaps_enqueue()
{
    wp_enqueue_script(
        'nk_iaps_ajax-script',
        NK_INFINITE_AJAX_POST_SCROLL_JS_PATH,
        array('jquery'),
        '1.0.0',
        true
    );
    wp_localize_script(
        'nk_iaps_ajax-script',
        'load_post_obj',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
        )
    );
}

function nk_iaps_previous_post_ajax_handler()
{
    $current_post_id = (int)$_POST['id'];
    $previous_post_id = nk_iaps_get_previous_post_id($current_post_id);
    if ($previous_post_id == 0)
        wp_die();
    $previous_article = nk_iaps_get_previous_article($previous_post_id);
    echo $previous_article;
    wp_die();
}

function nk_iaps_get_previous_post_id($post_id)
{
    global $post;
    $oldGlobal = $post;
    $post = get_post($post_id);
    $previous_post = get_previous_post();
    $post = $oldGlobal;
    if ('' == $previous_post) {
        return 0;
    }
    return $previous_post->ID;
}


function nk_iaps_get_previous_article($post_id)
{
    global $post;
    $oldGlobal = $post;
    $post = get_post($post_id);
    ob_start();
    get_template_part('template-parts/content/content', get_theme_mod('display_excerpt_or_full_post', 'excerpt'));
    $article = ob_get_contents();
    ob_end_clean();
    $post = $oldGlobal;
    return $article;
}