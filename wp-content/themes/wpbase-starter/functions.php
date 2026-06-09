<?php
/**
 * WordPressBase Starter Theme functions
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function wpbase_starter_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary' => 'Primary Menu',
    ));
}
add_action('after_setup_theme', 'wpbase_starter_setup');

/**
 * Enqueue styles
 */
function wpbase_starter_scripts() {
    wp_enqueue_style('wpbase-starter-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'wpbase_starter_scripts');

/**
 * Register widget area
 */
function wpbase_starter_widgets() {
    register_sidebar(array(
        'name'          => 'Sidebar',
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'wpbase_starter_widgets');
