<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$data = file_get_contents('php://input');
$data = json_decode($data, 1);
// $data = [
// 	'city' => 6,
// 	'cat' => 577,
// 	'firm' => 175,
// ];

$result = [
	'success' => false,
	'message' => 'Что-то пошло не так',
];

if (empty($data)) {
	$result['message'] = 'Данные не заполнены';
	echo json_encode($result, 320);
}

$args = [
	'post_type' => 'service',
	'posts_per_page' => -1,
];

$city_id = $data['city'];
$city_url = 'all_cities';
if (!empty($city_id) && $city_id !== 589) {
	if ($city = get_term_by('ID', $city_id, 'city')) {
		$city_url = $city->slug;
	}

	if ($city_url != 'all_cities') {
		$args['tax_query'][] = [
			'taxonomy' => 'city',
			'field' => 'ID',
			'terms' => $city_id,
		];
	}
}

$cat_id = $data['cat'];
$cat_url = 'all_categories';
if (!empty($cat_id) && $cat_id !== 587) {
	if ($cat = get_term_by('ID', $cat_id, 'categories')) {
		$cat_url = $cat->slug;
	}

	if ($cat_url != 'all_categories') {
		$args['tax_query'][] = [
			'taxonomy' => 'categories',
			'field' => 'ID',
			'terms' => $cat_id,
		];
	}
}

$firm_id = $data['firm'];
$firm_url = 'all_firms';
if (!empty($firm_id) && $firm_id !== 588) {
	if ($firm = get_term_by('ID', $firm_id, 'firms')) {
		$firm_url = $firm->slug;
	}

	if ($firm_url != 'all_firms') {
		$args['tax_query'][] = [
			'taxonomy' => 'firms',
			'field' => 'ID',
			'terms' => $firm_id,
		];
	}
}

$services = new WP_Query($args);

if ($services->have_posts()) {
	$result['success'] = true;
	$result['count'] = count($services->posts);
	$result['link'] = get_home_url() . '/services/' . $firm_url . '/' . $city_url . '/' . $cat_url;
	unset($result['message']);
} else {
	$result['count'] = 0;
	$result['link'] = '#';
	$result['message'] = 'Таких сервисов не найдено';
}

echo json_encode($result, 320);
