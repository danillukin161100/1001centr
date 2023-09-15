<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$data = file_get_contents('php://input');
$data = json_decode($data);
$result = [
	'response' => false,
	'message' => 'Что-то пошло не так',
];

if (empty($data)) {
	$result['message'] = 'Данные не заполнены';
	echo json_encode($result, 320);
}

$result['data'] = $data;

echo json_encode($result, 320);