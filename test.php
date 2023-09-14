<?php
header('Content-Type: text/plain');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

if (!current_user_can('administrator')) {
	header('Location: /');
}

$firms = get_terms([
	'taxonomy' => 'firms',
	'hide_empty' => false,
]);

foreach ($firms as $firm) {
	$image_path = getFirmImageName($firm->term_id, 1);
	// $is_exist = file_exists(getFirmImageName($firm->term_id, 1));
	// var_dump($is_exist);
	$is_exist = (file_exists(get_template_directory() . $image_path)) ? 'TRUE' : 'FALSE';
	// echo $firm->name . PHP_EOL;
	// echo 'http://l92235wp.beget.tech/wp-admin/term.php?taxonomy=firms&tag_ID=' . $firm->term_id . '&post_type=service&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dfirms%26post_type%3Dservice' . PHP_EOL;
	// echo $is_exist . PHP_EOL;
	echo preg_replace('/\/images\/logo\//', '/', $image_path) . PHP_EOL;
}
