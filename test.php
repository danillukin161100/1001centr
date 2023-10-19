<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

header('content-type: text/plain');

$services = new WP_Query([
	'post_type' => 'service',
	'posts_per_page' => -1,
]);

while ($services->have_posts()) : $services->the_post();
	$site = get_field('site');
	
	if (empty($site)) continue;
	
	preg_match('/http.*?:\/\//', $site, $matches);

	if (empty($matches)) {
		$site = 'http://' . $site;
	}

	echo trim($site) . "\n";
endwhile;
