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

$city_id = $data['city'];
$city = 'all_cities';
if (!empty($city_id)) {
	$args['tax_query'][] = [
		'taxonomy' => 'city',
		'field' => 'ID',
		'terms' => $city_id,
	];
	$city = get_term_by('ID', $city_id, 'city');
}

$cat_id = $data['cat'];
$cat = 'all_categories';
if (!empty($data['cat'])) {
	$args['tax_query'][] = [
		'taxonomy' => 'categories',
		'field' => 'ID',
		'terms' => $data['cat'],
	];
	$cat = get_term_by('ID', $cat_id, 'categories');
}

$firm_id = $data['firm'];
$firm = 'all_firms';
if (!empty($data['firm'])) {
	$args['tax_query'][] = [
		'taxonomy' => 'firms',
		'field' => 'ID',
		'terms' => $data['firm'],
	];
	$firm = get_term_by('ID', $firm_id, 'firms');
}

$services = new WP_Query($args);

if ($services->have_posts()) {
	$result['success'] = true;
	$result['count'] = count($services->posts);
	$result['link'] = get_home_url() . '/services/' . $city->slug . '/' . $cat->slug . '/' . $firm->slug;
	unset($result['message']);
}

echo json_encode($result, 320);
