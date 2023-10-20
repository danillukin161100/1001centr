<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

header('Content-type: application/json');

$cibaOptions = cibaFormsGetOptions();

function errorDie($msg = null) {
	$result = ['status' => 'error'];
	if (!empty($msg)) {
		$result['message'] = $msg;
	}
	echo json_encode($result, 320);
	die();
}

if (!isset($_POST) || empty($_POST)) {
	errorDie('Empty data');
}

if (empty($cibaOptions['api_key'])) {
	errorDie('Unknown API Key');
}

if (empty($cibaOptions['source_id'])) {
	errorDie('Unknown source_id');
}

$postdata = [
	'key' => $cibaOptions['api_key'],
	'method' => 'app_create',
	'source_id' => !empty($_POST['source_id']) ? $_POST['source_id'] : $cibaOptions['source_id'],
	'phone' => $_POST['phone'],
	'mango_name' => !empty($_POST['mango_name']) ? $_POST['mango_name'] : $cibaOptions['mango_name'],
	// 'test' => true
];

$url = 'https://cibacrm.com/api_v2.php';
$post = http_build_query($postdata);
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Connection: close'
));

$answer = curl_exec($ch);
$answer = json_decode($answer, true);

echo json_encode($answer, 320);