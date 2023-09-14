<?php
$city = get_query_var('city', 'all_cities');
$cat = get_query_var('categories', 'all_categories');
$firm = get_query_var('firms', 'all_firms');

$args = [
	'post_type' => 'service',
	'posts_per_page' => 8,
	'meta_key' => 'rate',
	'orderby'  => [
		'meta_value_num' => 'DESC',
	],
];

if ($city != 'all_cities') {
	$args['tax_query'][] = [
		'taxonomy' => 'city',
		'field' => 'slug',
		'terms' => $city,
	];
}

if ($cat != 'all_categories') {
	$args['tax_query'][] = [
		'taxonomy' => 'categories',
		'field' => 'slug',
		'terms' => $cat,
	];
}

if ($firm != 'all_firms') {
	$args['tax_query'][] = [
		'taxonomy' => 'firms',
		'field' => 'slug',
		'terms' => $firm,
	];
}

$services = new WP_Query($args);

// echo '<pre id="ahsvdjashd">';
// var_dump($city, $cat, $firm);
// print_r($services->posts);
// echo '</pre>';

if (!$services->have_posts()) {
	return false;
}
?>

<section class="services">
	<div class="services__container">
		<div class="services-control">
			<div class="services-control-inputBx"><input id="services-control-input" type="text" data-list=".services-box" placeholder="Введите адрес"></div>
			<!-- /.services-control-inputBx -->
			<div class="services-control-buttonBx">
				<button class="services-control-buttonBx__item active">Списком</button>
				<button class="services-control-buttonBx__item">На карте</button>
			</div>
			<!-- /.services-control-buttonBx -->
		</div>
		<!-- /.services-control -->
		<div class="services-wrapper">
			<div class="js-block-changer services-inner" id="demo">
				<div class="data-container services-box" id="data-container">

				</div>
				<!-- /.services-box -->
				<div id="pagination-container"></div>
			</div>
			<!-- /.services-inner -->

			<div class="js-block-changer services-map">
				<div class="_overlay-bg services-map-helper">
					<div class="services-map-box">
						<?php while ($services->have_posts()) : $services->the_post(); ?>
							<div class="services__item">
								<div class="services__item-titleBx">
									<?php if (get_field('moder')) { ?>
										<img src="<?= get_template_directory_uri() ?>/images/services/check.svg" alt="">
									<?php } ?>
									<a href="<?= get_the_permalink() ?>" class="services__item-title"><?= get_the_title() ?></a>
								</div>
								<?php if ($address = get_field('address')) { ?>
									<div class="services__item-info"><img src="<?= get_template_directory_uri() ?>/images/services/address.svg" alt="">
										<div class="services__item-info-main">
											<p class="services__item-info-address"><?= $address ?></p>
										</div>
									</div>
								<?php } ?>
								<div class="services__item-info"><img src="<?= get_template_directory_uri() ?>/images/services/clock.svg" alt="">
									<div class="services__item-info-main">
										<p>
											<?php
											$weekdays = (!empty(get_field('weekdays'))) ? get_field('weekdays') : ' - ';
											$weekend = (!empty(get_field('weekend'))) ? get_field('weekend') : ' - ';
											?>
											Пн-Пт: <?= $weekdays ?> <br>
											Сб-Вс: <?= $weekend ?>
										</p>
									</div>
								</div>
								<?php if ($rate = get_field('rate')) { ?>
									<div class="services__item-info"><img src="<?= get_template_directory_uri() ?>/images/services/star.svg" alt="">
										<div class="services__item-info-main">
											<p><?= $rate ?></p>
										</div>
									</div>
								<?php } ?>
								<div class="services__item-buttonBx">
									<button class="pb services__item-feedback" data-type="repair">Оставить заявку
									</button>
									<a href="<?= (($phones = get_field('phones')) && !empty($phones[0]['number'])) ? 'tel: ' . phoneLink($phones[0]['number']) : '#!' ?>" class="services__item-phone">
										<img src="<?= get_template_directory_uri() ?>/images/services/phone.svg" alt="">
									</a>
									<a href="#" class="services__item-phone">Открыть</a>
								</div>
							</div>
						<?php endwhile; ?>
						<div class="button-close services-map-box__cross"><i class="fa-solid fa-xmark"></i></div>
					</div>
				</div>
				<!-- /.services-map-box -->
				<div class="services-mapBx">
					<div id="map"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.services__container -->
</section>
<!-- /.services -->