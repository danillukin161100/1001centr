<?php

/**
 * thousand_one functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package thousand_one
 */

/* Icnludes */
require_once __DIR__ . '/inc/FooterWalkerNavMenu.php';
require_once __DIR__ . '/inc/routes.php';
require_once __DIR__ . '/inc/redirects.php';

/* Version */
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
	// add_theme_support('title-tag');

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

	/* Функция для форматирования телефона в ссылку */
	function phoneLink($phone)
	{
		$phone = preg_replace('/[^+0-9]/', '', $phone);
		return urlencode($phone);
	}

	/* Загрузка внешних изображений по URL в медиабиблиотеку WP */
	function uploadImage($image_url)
	{
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$upload_dir = wp_upload_dir();
		$image_data = file_get_contents($image_url);
		$filename = basename($image_url);

		if (wp_mkdir_p($upload_dir['path'])) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		file_put_contents($file, $image_data);

		$wp_filetype = wp_check_filetype($filename, null);

		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => sanitize_file_name($filename),
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment($attachment, $file);
		$attach_data = wp_generate_attachment_metadata($attach_id, $file);
		wp_update_attachment_metadata($attach_id, $attach_data);

		return $attach_id;
	}

	/* Получить мета поле Title/H1/Description */
	function getMetaTag($tag)
	{
		if (!empty(get_field('meta_' . $tag))) {
			return get_field('meta_' . $tag);
		} elseif (is_archive('service')) {
			$city = get_query_var('city', 'all_cities');
			$cat = get_query_var('categories', 'all_categories');
			$firm = get_query_var('firms', 'all_firms');
			$result = 'Сервисные центры';
			$result_city = null;
			if ($city != 'all_cities') {
				$city = get_term_by('slug', $city, 'city');
				$city_label = (!empty(get_field('variant_2', $city))) ? get_field('variant_2', $city) : $city->name;
				$result_city = ' в ' . $city_label;
				// $result_city = mb_strtolower($result_city);
			}

			$result_cat = null;
			if ($cat != 'all_categories') {
				$cat = get_term_by('slug', $cat, 'categories');
				$cat_label = (!empty(get_field('variant_2', $cat))) ? get_field('variant_2', $cat) : $cat->name;
				$result_cat = ' по ремонту ' . $cat_label;
				$result_cat = mb_strtolower($result_cat);
			} else {
				$result_cat = ' по ремонту техники ';
			}

			$result_firm = null;
			if ($firm != 'all_firms') {
				$firm = get_term_by('slug', $firm, 'firms');
				$result_firm = ' ' . $firm->name;
			}

			$result .= $result_cat . $result_firm . $result_city;

			return $result;
		}
	}

	/* Получить URL изображения категории */
	function getCategoryImageName($term_id, $brand = false)
	{
		$dir_path = get_template_directory();
		$dir_url = get_template_directory_uri();
		$term = get_term($term_id, 'categories');
		$image_name = get_field('image_name', $term);
		$image_path = "/images/logo/$brand/$image_name.png";

		if (empty($image_name)) {
			$image_name = $term->slug . '.png';
		}
		if (!$brand) {
			$image_path = "/images/logo/nobrand/$image_name.png";
		}
		if (!file_exists($dir_path . $image_path)) {
			$image_path = "/images/no-photo.png";
		}

		return $dir_url . $image_path;
	}

	/* Получить URL изображения бренда */
	function getFirmImageName($term_id, $test = false)
	{
		$dir_path = get_template_directory();
		$dir_url = get_template_directory_uri();
		$term = get_term($term_id, 'firms');
		$image_name = (!empty(get_field('image_name', $term))) ? get_field('image_name', $term) : 'logo-' . $term->slug . '.png';
		$image_path = "/images/logo/brands/$image_name";

		if ($test) {
			return $image_path;
		}

		if (!file_exists($dir_path . $image_path)) {
			$image_path = "/images/no-photo.png";
		}

		return $dir_url . $image_path;
	}

	/* Получить URL на бренд */
	function getFirmsPermalink($firm)
	{
		if (gettype($firm) == 'integer') {
			$firm = get_term($firm, 'firms');
		}

		$city_slug = get_query_var('city', 'all_cities');
		$cat_slug = get_query_var('categories', 'all_categories');

		if ($city_slug == 'all_cities' && !empty($_COOKIE['city'])) {
			$cookie_city = get_term($_COOKIE['city'], 'city');
			$city_slug = $cookie_city->slug;
		}

		$url = '/services/' . $firm->slug;

		if ($city_slug != 'all_cities') {
			$url .= '/' . $city_slug;
		}
		if ($cat_slug != 'all_categories') {
			$url .= '/' . $cat_slug;
		}

		return $url;
	}

	/* Получить URL на город */
	function getCitiesPermalink($city)
	{
		if (gettype($city) == 'integer') {
			$city = get_term($city, 'city');
		}

		$firm_slug = get_query_var('firms', 'all_firms');
		$cat_slug = get_query_var('categories', 'all_categories');
		$url = '/services/' . $firm_slug . '/' . $city->slug;

		if ($cat_slug != 'all_categories') {
			$url .= '/' . $cat_slug;
		}

		return $url;
	}

	/* Получить URL на категорию */
	function getCategoriesPermalink($cat)
	{
		if (gettype($cat) == 'integer') {
			$cat = get_term($cat, 'categories');
		}

		$firm_slug = get_query_var('firms', 'all_firms');
		$city_slug = get_query_var('city', 'all_cities');

		if ($city_slug == 'all_cities' && !empty($_COOKIE['city'])) {
			$cookie_city = get_term($_COOKIE['city'], 'city');
			$city_slug = $cookie_city->slug;
		}

		$url = '/services/' . $firm_slug . '/' . $city_slug . '/' . $cat->slug;

		return $url;
	}
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
	wp_enqueue_style('thousand_one-color', get_template_directory_uri() . '/static/css/color.css', array(), _S_VERSION);
	wp_enqueue_style('thousand_one-main', get_template_directory_uri() . '/css/style.css', array(), _S_VERSION);
	wp_enqueue_style('thousand_one-all', get_template_directory_uri() . '/static/css/all.css', array(), _S_VERSION);

	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri() . '/static/plugins/jquery.min.js', array(), '3.4.1', true);
	wp_enqueue_script('jquery-maskedinput', get_template_directory_uri() . '/static/plugins/jquery.maskedinput.min.js', array(), '1.4.1', true);
	wp_enqueue_script('pagination', get_template_directory_uri() . '/static/plugins/pagination.min.js', array(), '2.6.0', true);
	wp_enqueue_script('jquery-hideseek', get_template_directory_uri() . '/static/plugins/jquery.hideseek.min.js', array(), '0.8.0', true);
	wp_enqueue_script('api-maps', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=b1636bea-a71f-4f63-83f5-554063b5a20b', array(), '2.1', true);
	wp_enqueue_script('thousand_one-app', get_template_directory_uri() . '/js/app.js', array(), _S_VERSION, true);
});
