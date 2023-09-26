<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$data = file_get_contents('php://input');
$data = json_decode($data, true);

// $data = [
// 	'service_id' => 2848,
// 	'x' => 100.312312312,
// 	'y' => 100.123,
// ];

$result = [
	'success' => false,
	'message' => 'Что-то пошло не так',
];

if (empty($data)) {
	$result['message'] = 'Данные не заполнены';
	echo json_encode($result, 320);
	die();
}

if (empty($data['service_id'])) {
	$result['message'] = 'Не передан идентификатор сервиса';
	echo json_encode($result, 320);
	die();
}

if (empty($data['x'])) {
	$result['message'] = 'Не передан X сервиса';
	echo json_encode($result, 320);
	die();
}

if (empty($data['y'])) {
	$result['message'] = 'Не передан Y сервиса';
	echo json_encode($result, 320);
	die();
}

$service = get_post($data['service_id']);

if (empty($service)) {
	$result['message'] = 'Такого сервиса не существует';
	echo json_encode($result, 320);
	die();
}

$coords = $data['x'] . '|' . $data['y'];
$old_coords = get_field('coords', $data['service_id']);

if ($coords == $old_coords) {
	$result['success'] = true;
	$result['message'] = 'Новый данные совпадают с имеющимися';
	echo json_encode($result, 320);
	die();
}

if ($update = update_field('coords', $coords, $data['service_id'])) {
	$result['success'] = true;
	$result['message'] = 'Координаты успешно обновлены';
}

echo json_encode($result, 320);
