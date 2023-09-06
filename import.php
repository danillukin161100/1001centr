<?php
header('Content-Type: text/plain');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

if (!current_user_can('administrator')) {
	header('Location: /');
}

// citiesImport();
// firmsImport();
// categoriesImport();
// serviceImport();
// deleteAllServices();

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
	$services = array_values($services);

	$step = 2500;
	$iteration = 1;
	$iteration--;
	$start = $step * $iteration;
	$end = $start + $step - 1;

	foreach ($services as $item_key => $item) {
		if ($item_key < $start) continue;

		/* Массив телефонов */
		$phones = [];
		foreach (explode(';', $item['telephones']) as $phone) {
			$phones[] = $phone;
		}
		foreach (explode(';', $item['phone']) as $phone) {
			$phones[] = $phone;
		}
		$phones = array_unique($phones);
		$phones = array_filter($phones);
		$phones = array_values($phones);

		$post_data = [
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => $item['description'],
			'post_date'      => $item['created_at'],
			'post_date_gmt'  => $item['created_at'],
			'post_name'      => strtolower($item['name_url']),
			'post_status'    => 'publish',
			'post_title'     => $item['name'],
			'post_type'      => 'service',
		];

		/* Категории */
		if (!empty($item['categories'])) {
			foreach ($item['categories'] as $cat) {
				$post_data['tax_input']['categories'][] = strtolower($cat['name_url']);
			}
		}
		if (!empty($item['city_list'])) {
			foreach ($item['city_list'] as $city) {
				$post_data['tax_input']['city'][] = strtolower($city['name_url']);
			}
		}
		if (!empty($item['firms'])) {
			foreach ($item['firms'] as $firm) {
				$post_data['tax_input']['firms'][] = strtolower($firm['name_url']);
			}
		}

		/* Доп поля */
		if (!empty($item['type'])) $post_data['meta_input']['type'] = $item['type'];
		if (!empty($item['moder'])) $post_data['meta_input']['moder'] = $item['moder'];
		if (!empty($item['rate'])) $post_data['meta_input']['rate'] = $item['rate'];
		if (!empty($item['address'])) $post_data['meta_input']['address'] = $item['address'];
		if (!empty($item['website'])) $post_data['meta_input']['site'] = $item['website'];
		if (!empty($item['email'])) $post_data['meta_input']['email'] = $item['email'];
		if (!empty($item['weekdays'])) $post_data['meta_input']['weekdays'] = $item['weekdays'];
		if (!empty($item['weekend'])) $post_data['meta_input']['weekend'] = $item['weekend'];
		if (!empty($item['schedule'])) $post_data['meta_input']['schedule'] = $item['schedule'];
		if (!empty($item['home_repairs'])) $post_data['meta_input']['home_repairs'] = $item['home_repairs'];
		if (!empty($item['delivery_to_client'])) $post_data['meta_input']['delivery_to_client'] = $item['delivery_to_client'];
		if (!empty($item['sale_of_spare'])) $post_data['meta_input']['sale_of_spare'] = $item['sale_of_spare'];
		if (!empty($item['setting_to_client'])) $post_data['meta_input']['setting_to_client'] = $item['setting_to_client'];
		if (!empty($phones)) {
			$post_data['meta_input']['phones'] = count($phones);
			foreach ($phones as $key => $phone) {
				$post_data['meta_input']['phones_' . $key . '_number'] = $phone;
			}
		}

		/* Загрузка изображений */
		if (!empty($item['image_inn'])) {
			$url = 'https://1001centr.ru' . $item['image_inn'];
			$attach_id = uploadImage($url);
			$post_data['meta_input']['image_inn'] = $attach_id;
		}
		if (!empty($item['image_ogrn'])) {
			$url = 'https://1001centr.ru' . $item['image_ogrn'];
			$attach_id = uploadImage($url);
			$post_data['meta_input']['image_ogrn'] = $attach_id;
		}
		if (!empty($item['image_passport'])) {
			$url = 'https://1001centr.ru' . $item['image_passport'];
			$attach_id = uploadImage($url);
			$post_data['meta_input']['image_passport'] = $attach_id;
		}

		$post_id = wp_insert_post($post_data);

		if (!empty($item['service_img']) && $post_id) {
			$url = 'https://1001centr.ru' . $item['service_img'];
			$attach_id = uploadImage($url);
			set_post_thumbnail($post_id, $attach_id);
		}

		echo "[$item_key] -> $post_id \n";
		
		if ($item_key >= $end) break;
	}
}

function deleteAllServices() {
	$posts = get_posts([
		'post_type' => 'service',
		'posts_per_page' => -1,
	]);
	foreach ($posts as $p) {
		$delete = wp_delete_post($p->ID, 1);
		print_r($delete);
		echo "\n\n\n";
	}
}