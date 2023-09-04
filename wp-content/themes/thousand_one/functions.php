<?php

/**
 * thousand_one functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package thousand_one
 */

if (!defined('_S_VERSION')) {
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
add_action('after_setup_theme', function () {
	/*
	* Make theme available for translation.
	* Translations can be filed in the /languages/ directory.
	* If you're building a theme based on thousand_one, use a find and replace
	* to change 'thousand_one' to the name of your theme in all the template files.
	*/
	load_theme_textdomain('thousand_one', get_template_directory() . '/languages');

	/* Add default posts and comments RSS feed links to head. */
	add_theme_support('automatic-feed-links');

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support('title-tag');

	/*
	* Enable support for Post Thumbnails on posts and pages.
	*
	* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	*/
	add_theme_support('post-thumbnails');

	/* This theme uses wp_nav_menu() in one location. */
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'thousand_one'),
		)
	);

	/*
	* Switch default core markup for search form, comment form, and comments
	* to output valid HTML5.
	*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	/* Set up the WordPress core custom background feature. */
	add_theme_support(
		'custom-background',
		apply_filters(
			'thousand_one_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	/* Add theme support for selective refresh for widgets. */
	add_theme_support('customize-selective-refresh-widgets');

	/* Add support for core custom logo. */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
});

/* Register widget area */
add_action('widgets_init', function () {
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'thousand_one'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'thousand_one'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
});

/* Enqueue scripts and styles */
add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style('thousand_one-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('thousand_one-style', 'rtl', 'replace');

	wp_enqueue_script('thousand_one-navigation', get_template_directory_uri() . '/js/script.js', array(), _S_VERSION, true);
});
