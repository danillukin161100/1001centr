<?php

// add_action('init', function () {
// 	flush_rewrite_rules();

// 	/* Города */
// 	add_rewrite_rule('^(service)s/([^/]*)/?$', 'index.php?post_type=$matches[1]&city=$matches[2]', 'top');

// 	/* Города -> Категории */
// 	add_rewrite_rule('^(service)s/([^/]*)/([^/]*)/?$', 'index.php?post_type=$matches[1]&city=$matches[2]&categories=$matches[3]', 'top');

// 	/* Города -> Категории -> Бренды */
// 	add_rewrite_rule('^(service)s/([^/]*)/([^/]*)/([^/]*)/?$', 'index.php?post_type=$matches[1]&city=$matches[2]&categories=$matches[3]&firms=$matches[4]', 'top');
	
// 	add_filter('query_vars', function ($vars) {
// 		$vars[] = 'city';
// 		$vars[] = 'categories';
// 		$vars[] = 'firms';
// 		$vars = array_unique($vars);
// 		return $vars;
// 	});
// });


add_action('init', function () {
	flush_rewrite_rules();

	/* Бренд */
	add_rewrite_rule('^(service)s/([^/]*)/?$', 'index.php?post_type=$matches[1]&firms=$matches[2]', 'top');

	/* Бренд -> Города */
	add_rewrite_rule('^(service)s/([^/]*)/([^/]*)/?$', 'index.php?post_type=$matches[1]&firms=$matches[2]&city=$matches[3]', 'top');

	/* Бренд -> Города -> Категории */
	add_rewrite_rule('^(service)s/([^/]*)/([^/]*)/([^/]*)/?$', 'index.php?post_type=$matches[1]&firms=$matches[2]&city=$matches[3]&categories=$matches[4]', 'top');
	
	add_filter('query_vars', function ($vars) {
		$vars[] = 'firms';
		$vars[] = 'city';
		$vars[] = 'categories';
		$vars = array_unique($vars);
		return $vars;
	});
});
