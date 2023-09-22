<?php
add_action('init', function () {

	function getSlugTerms($terms)
	{
		$result = [];
		foreach ($terms as $term) [
			$result[] = $term->slug,
		];
		return $result;
	}

	$categories = get_terms([
		'taxonomy' => 'categories',
		'hide_empty' => false,
	]);
	$categories_slug = getSlugTerms($categories);

	$firms = get_terms([
		'taxonomy' => 'firms',
		'hide_empty' => false,
	]);
	$firms_slug = getSlugTerms($firms);

	$cities = get_terms([
		'taxonomy' => 'city',
		'hide_empty' => false,
	]);
	$cities_slug = getSlugTerms($cities);

	// $query_url = str_replace('/services/', '', $_SERVER['REQUEST_URI']);
	$query_url = trim($_SERVER['REQUEST_URI'], '/');
	$query_url = explode('/', $query_url);

	$is_redirect = false;
	if (!in_array($query_url[1], $firms_slug)) {
		$is_redirect = true;
	}
	if (!in_array($query_url[2], $cities_slug)) {
		$is_redirect = true;
	}
	if (!in_array($query_url[3], $categories_slug)) {
		$is_redirect = true;
	}

	if ($query_url[0] == 'services' && $is_redirect) {
		unset($query_url[0]);
		$query_url = array_values($query_url);
		$query_data = [];

		foreach ($query_url as $q) {
			if (in_array($q, $categories_slug)) {
				$query_data['categories'] = $q;
			}
			if (in_array($q, $firms_slug)) {
				$query_data['firms'] = $q;
			}
			if (in_array($q, $cities_slug)) {
				$query_data['city'] = $q;
			}
		}

		$cat_slug = (!empty($query_data['categories'])) ? $query_data['categories'] : 'all_categories';
		$firm_slug = (!empty($query_data['firms'])) ? $query_data['firms'] : 'all_firms';
		$city_slug = (!empty($query_data['city'])) ? $query_data['city'] : 'all_city';

		$old_url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/services/' . $firm_slug . '/' . $city_slug . '/' . $cat_slug . '/';

		if ($old_url !== $url) {
			wp_safe_redirect($url);
			exit();
		}
	}
});
