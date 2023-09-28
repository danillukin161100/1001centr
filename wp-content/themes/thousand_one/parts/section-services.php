<?php
$city = get_query_var('city', 'all_cities');
$cat = get_query_var('categories', 'all_categories');
$firm = get_query_var('firms', 'all_firms');

$args = [
	'post_type' => 'service',
	'posts_per_page' => 8,
	// 'meta_key' => 'rate',
	// 'orderby'  => [
	// 	'meta_value_num' => 'DESC',
	// ],
];

if (!empty($city) && $city != 'all_cities') {
	$args['tax_query'][] = [
		'taxonomy' => 'city',
		'field' => 'slug',
		'terms' => $city,
	];
}

if (!empty($cat) && $cat != 'all_categories') {
	$args['tax_query'][] = [
		'taxonomy' => 'categories',
		'field' => 'slug',
		'terms' => $cat,
	];
}

if (!empty($firm) && $firm != 'all_firms') {
	$args['tax_query'][] = [
		'taxonomy' => 'firms',
		'field' => 'slug',
		'terms' => $firm,
	];
}

$services = new WP_Query($args);

$services_arr = [];

if ($services->have_posts()) {
	while ($services->have_posts()) : $services->the_post();

		$service_data = (object) [
			'id' => get_the_ID(),
			'weekdays' => (!empty(get_field('weekdays'))) ? get_field('weekdays') : ' - ',
			'weekend' => (!empty(get_field('weekend'))) ? get_field('weekend') : ' - ',
			'link' => get_permalink(),
		];

		if ($moder = get_field('moder')) {
			$service_data->moder = $moder;
		}

		if ($address = get_field('address')) {
			$service_data->address = $address;
		}

		$city = null;
		if (($cities = get_the_terms(get_the_ID(), 'city')) && !empty($cities)) {
			$service_data->city = array_shift($cities);
		}

		if ($rate = get_field('rate')) {
			$service_data->rate = $rate;
		}

		if (($phones = get_field('phones')) && !empty($phones[0]['number'])) {
			$service_data->phone = phoneLink($phones[0]['number']);
		}

		$services_arr[] = $service_data;
	endwhile;
}
wp_reset_query();

usort($services_arr, function($a, $b){
	if ($a->rate == $b->rate) return 0;
	return ($a->rate > $b->rate) ? -1 : 1;
});

echo '<pre id="ahsvdjashd">';
// var_dump($city, $cat, $firm);
// print_r($services);
echo '</pre>';

?>

<section class="services">
	<div class="services__container">
		<?php if (!empty($services_arr)) { ?>
			<div class="services-control">
				<div class="services-control-inputBx">
					<input id="services-control-input" type="text" data-list=".services-box" placeholder="Введите адрес">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9.16667 15.8333C12.8486 15.8333 15.8333 12.8486 15.8333 9.16667C15.8333 5.48477 12.8486 2.5 9.16667 2.5C5.48477 2.5 2.5 5.48477 2.5 9.16667C2.5 12.8486 5.48477 15.8333 9.16667 15.8333Z" stroke="#656F7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						<path d="M17.5 17.5L13.875 13.875" stroke="#656F7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</div>
				<!-- /.services-control-inputBx -->
				<div class="services-control-buttonBx">
					<button class="services-control-buttonBx__item active">Списком</button>
					<button class="services-control-buttonBx__item">На карте</button>
				</div>
				<!-- /.services-control-buttonBx -->
			</div>
			<!-- /.services-control -->
			<div class="services-wrapper">
                <div class="services-inner js-block-changer" id="demo">
                    <ul class="services-box data-container" id="data-container">
                        <li>
                            <div class="services__item">
                                <a href="${item.service_link}" class="services__item-titleBx">
                                    <img src="<?= get_template_directory_uri() ?>/images/services/check.svg" alt="">
                                    <p class="services__item-title">Барон</p>
                                </a>
                                <div class="services__item-info"><img src="<?= get_template_directory_uri() ?>/images/services/address.svg" alt="">
                                    <div class="services__item-info-main">
                                        <p>Адрес</p>
                                    </div>
                                </div>
                                <div class="services__item-info">
                                    <img src="<?= get_template_directory_uri() ?>/images/services/clock.svg" alt="">
                                    <div class="services__item-info-main">
                                        <p>
                                            Пн-Пт: 10:00 - 21:00 <br>
                                            Сб-Вс: 10:00 - 21:00
                                        </p>
                                    </div>
                                </div>

                                <div class="services__item-info"><img src="<?= get_template_directory_uri() ?>/images/services/star.svg" alt="">
                                    <div class="services__item-info-main">
                                        <p>5.0 (15)</p>
                                    </div>
                                </div>
                                <div class="services__item-buttonBx">
                                    <button class="services__item-feedback pb" data-type="repair">Оставить заявку</button>
                                    <a href="tel: +7 (969) 999-42-80" class="services__item-phone"><img src="<?= get_template_directory_uri() ?>/images/services/phone.svg" alt=""></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <!-- /.services-box -->
                    <div class="services-helper">

                        <div class="paginationjs">
                            <div class="paginationjs-prev"></div>
                            <ul>
                                <li>1</li>
                                <li>...</li>
                                <li>2</li>
                            </ul>
                            <div class="paginationjs-next"></div>
                        </div>
                    </div>
                    <!-- /.services-helper -->
                </div>
                <!-- /.services-inner -->

				<div class="js-block-changer services-map">
					<div class="_overlay-bg services-map-helper">
						<div class="services-map-box">
							<?php foreach ($services_arr as $service) { ?>
								<div class="services__item" data-service-id="<?= $service->id ?>">
									<div class="services__item-titleBx">
										<?php if (!empty($service->moder)) { ?>
											<img src="<?= get_template_directory_uri() ?>/images/services/check.svg" alt="">
										<?php } ?>
										<a href="<?= get_the_permalink() ?>" class="services__item-title"><?= get_the_title() ?></a>
									</div>
									<?php if (!empty($service->address)) { ?>
										<div class="services__item-info address"><img src="<?= get_template_directory_uri() ?>/images/services/address.svg" alt="">
											<div class="services__item-info-main">
												<p class="services__item-info-address" data-city="<?= (!empty($service->city)) ? $service->city->name : '' ?>"><?= $service->address ?></p>
											</div>
										</div>
									<?php } ?>
									<div class="services__item-info time"><img src="<?= get_template_directory_uri() ?>/images/services/clock.svg" alt="">
										<div class="services__item-info-main">
											<p>
												Пн-Пт: <?= $service->weekdays ?> <br>
												Сб-Вс: <?= $service->weekend ?>
											</p>
										</div>
									</div>
									<?php if (!empty($service->rate)) { ?>
										<div class="services__item-info rate"><img src="<?= get_template_directory_uri() ?>/images/services/star.svg" alt="">
											<div class="services__item-info-main">
												<p><?= $service->rate ?></p>
											</div>
										</div>
									<?php } ?>
									<div class="services__item-buttonBx">
										<button class="pb services__item-feedback" data-type="repair">Оставить заявку</button>
										<?php if (!empty($service->phone)) { ?>
											<a href="tel:<?= $service->phone ?>" class="services__item-phone">
												<img src="<?= get_template_directory_uri() ?>/images/services/phone.svg" alt="">
											</a>
										<?php } ?>
										<a href="<?= $service->link ?>" class="services__item-phone">Открыть</a>
									</div>
								</div>
							<?php } ?>
							<div class="button-close services-map-box__cross"><i class="fa-solid fa-xmark"></i></div>
						</div>
					</div>
					<!-- /.services-map-box -->
					<div class="services-mapBx">
						<div id="map"></div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<p>Сервисных центров не найдено</p>
		<?php } ?>
	</div>
	<!-- /.services__container -->
</section>
<!-- /.services -->