<?php
header('Content-Type: text/plain');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

if (!current_user_can('administrator')) {
	header('Location: /');
}

// citiesImport();
// firmsImport();
// categoriesImport();
serviceImport();

/* Импорт городов */
function citiesImport()
{
	$json = @file_get_contents(get_template_directory() . '/data/cities.json');
	if (empty($json)) {
		echo 'empty data';
		die();
	}
	$cities = json_decode($json, 1);

	foreach ($cities as $item) {
		$insert = wp_insert_term($item['name'], 'city', array(
			'slug' => $item['name_url'],
		));

		update_term_meta($insert['term_id'], 'variant_1', $item['name_variant_1']);
		update_term_meta($insert['term_id'], 'variant_2', $item['name_variant_2']);
		update_term_meta($insert['term_id'], 'variant_3', $item['name_variant_3']);

		print_r($insert);
	}
}

/* Импорт брендов */
function firmsImport()
{
	$json = @file_get_contents(get_template_directory() . '/data/firms.json');
	if (empty($json)) {
		echo 'empty data';
		die();
	}
	$firms = json_decode($json, 1);

	foreach ($firms as $item) {
		$insert = wp_insert_term($item['name'], 'firms', array(
			'slug' => $item['name_url'],
		));

		print_r($insert);
	}
}

/* Импорт категорий */
function categoriesImport()
{
	$json = @file_get_contents(get_template_directory() . '/data/categories.json');
	if (empty($json)) {
		echo 'empty data';
		die();
	}
	$categories = json_decode($json, 1);

	foreach ($categories as $item) {
		$insert = wp_insert_term($item['name'], 'categories', array(
			'slug' => $item['name_url'],
		));

		print_r($insert);
	}
}


/* Импорт сервисных центров */
function serviceImport()
{
	$json = @file_get_contents(get_template_directory() . '/data/service_centers.json');
	if (empty($json)) {
		echo 'empty data';
		die();
	}
	$services = json_decode($json, 1);

	foreach ($services as $item) {
		if (empty($item['image_passport'])) continue;

		print_r($item);

		/* Массив телефонов */
		$phones = [];
		foreach (explode(';', $item['telephones']) as $phone) {
			$phones[] = $phone;
		}
		foreach (explode(';', $item['phone']) as $phone) {
			$phones[] = $phone;
		}
		$phones = array_unique($phones);

		// break;
	}
}