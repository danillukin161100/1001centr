<?php
get_header();

$city = get_query_var('city', 'all_cities');
$cat = get_query_var('categories', 'all_categories');
$firm = get_query_var('firms', 'all_firms');

get_template_part('parts/section', 'content');
get_template_part('parts/section', 'control');
get_template_part('parts/section', 'services');

if (empty($cat) || $cat == 'all_categories') {
	get_template_part('parts/section', 'technics');
}

if (empty($firm) || $firm == 'all_firms') {
	get_template_part('parts/section', 'brands');
}

if (empty($city) || $city == 'all_cities') {
	get_template_part('parts/section', 'guide');
}

get_template_part('parts/section', 'feedback');

get_footer();
