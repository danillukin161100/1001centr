<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$data = file_get_contents('php://input');
$data = json_decode($data);
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

$city_id = $data->city;
$city_url = 'all_cities';

if ($city_id) {
	$args['tax_query'][] = [
		'taxonomy' => 'city',
		'field' => 'ID',
		'terms' => $city_id,
	];
	if ($city = get_term_by('ID', $city_id, 'city')) {
		$city_url = $city->slug;
	}
}

$cat_id = $data->cat;
$cat_url = 'all_categories';
if ($cat_id) {
	$args['tax_query'][] = [
		'taxonomy' => 'categories',
		'field' => 'ID',
		'terms' => $cat_id,
	];
	if ($cat = get_term_by('ID', $cat_id, 'categories')) {
		$cat_url = $cat->slug;
	}
}

$firm_id = $data->firm;
$firm_url = 'all_firms';
if ($firm_id) {
	$args['tax_query'][] = [
		'taxonomy' => 'firms',
		'field' => 'ID',
		'terms' => $firm_id,
	];
	if ($firm = get_term_by('ID', $firm_id, 'firms')) {
		$firm_url = $firm->slug;
	}
}

$services = new WP_Query($args);

if ($services->have_posts()) {
	$result['success'] = true;
	$result['count'] = count($services->posts);
	$result['link'] = get_home_url() . '/services/' . $city_url . '/' . $cat_url . '/' . $firm_url;
	unset($result['message']);
}

echo json_encode($result, 320);
