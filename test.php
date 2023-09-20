<?php
header('Content-Type: text/plain');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

if (!current_user_can('administrator')) {
	header('Location: /');
}

// $services = new WP_Query([
// 	'post_type' => 'service',
// 	'posts_per_page' => -1,
// 	'include' => [2963, 2965],
// ]);

// if ($services->have_posts()) {
// 	foreach ($services->posts as $key => $service) {
// 		$_phones = get_post_meta($service->ID, '_phones', 'field_64f73786918c5');
// 		if (empty($_phones)) {
// 			$update = update_post_meta($service->ID, '_phones', 'field_64f73786918c5');
// 			var_dump($update);
// 		}
// 	}
// }
// wp_reset_query();


// $c_service = get_post(2963);
// $e_service = get_post(2965);

// update_post_meta($e_service->ID, '_phones', 'field_64f73786918c5');

// print_r(get_post_meta($c_service->ID, '_phones'));
// print_r(get_post_meta($e_service->ID, '_phones'));
