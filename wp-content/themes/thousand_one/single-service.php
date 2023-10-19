<?php
get_header();

$weekday = date('w');
$weekdays = (!empty(get_field('weekdays'))) ? get_field('weekdays') : ' - ';
$weekend = (!empty(get_field('weekend'))) ? get_field('weekend') : ' - ';
$week = ($weekday <= 5) ? explode('-', $weekdays) : explode('-', $weekend);
$is_open = null;
if (!empty($week) && count($week) == 2) {
	$now = time();
	$open_to = $week[1];
	$work_time = strtotime($open_to);
	$is_open = $now < $work_time;
}

$city = null;
if (($cities = get_the_terms(get_the_ID(), 'city')) && !empty($cities)) {
	$city = array_shift($cities);
}
$address = get_field('address');

$coords = get_field('coords');
$coords = explode('|', $coords);

$content = get_the_content();
$primaries = [];
if ($home_repairs = get_field('home_repairs')) {
	$primaries['home_repairs'] = $home_repairs;
}
if ($setting_to_client = get_field('setting_to_client')) {
	$primaries['setting_to_client'] = $setting_to_client;
}
if ($delivery_to_client = get_field('delivery_to_client')) {
	$primaries['delivery_to_client'] = $delivery_to_client;
}
if ($sale_of_spare = get_field('sale_of_spare')) {
	$primaries['sale_of_spare'] = $sale_of_spare;
}

$mail = get_field('email');
$site = get_field('site');
?>
<section class="service" data-service-id="<?= get_the_ID() ?>">
	<div class="service__container">
		<div class="service-inner">
			<div class="service-main">
				<div class="service-helper">
					<div class="service-titleBx">
						<div class="service-titleBx__item">
							<?php if ($moder = get_field('moder')) { ?>
								<span class="service__icon">
									<img src="<?= get_template_directory_uri() ?>/images/service/check.svg" alt="">
								</span>
							<?php } ?>
							<span class="service__title"><?= get_the_title() ?></span>
						</div>
						<?php if ($rate = get_field('rate')) { ?>
							<div class="service-titleBx__item">
								<span class="service__star">
									<img src="<?= get_template_directory_uri() ?>/images/service/star.svg" alt="">
								</span>
								<span class="service__rate"><?= $rate ?></span>
							</div>
						<?php } ?>
					</div>
					<!-- /.service-titleBx -->
					<div class="service-map">
						<div id="service-map" class="js-map" data-addr="<?= $address ?>" <?= (!empty($coords[0])) ? 'data-x="' . $coords[0] . '"' : '' ?> <?= (!empty($coords[1])) ? 'data-y="' . $coords[1] . '"' : '' ?>></div>
					</div>
				</div>
				<?php if (!empty($content) || !empty($primaries)) { ?>
					<div class="section_m about">
						<div class="section_white about-inner">
							<p class="title about__title">О сервисе</p>
							<?php if (!empty($content)) { ?>
								<div class="about__desc"><?= apply_filters('the_content', $content) ?></div>
							<?php } ?>
							<!-- /.about__desc -->
							<?php if (!empty($primaries)) { ?>
								<div class="about-box">
									<?php if (!empty($primaries['home_repairs'])) { ?>
										<div class="about__item">
											<div class="about__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/about/1.svg" alt=""></div>
											<p class="about__item-title">Выезд мастера на дом</p>
										</div>
									<?php } ?>
									<?php if (!empty($primaries['setting_to_client'])) { ?>
										<div class="about__item">
											<div class="about__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/about/2.svg" alt=""></div>
											<p class="about__item-title">Установка и подключение</p>
										</div>
									<?php } ?>
									<?php if (!empty($primaries['delivery_to_client'])) { ?>
										<div class="about__item">
											<div class="about__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/about/3.svg" alt=""></div>
											<p class="about__item-title">Доставка оборудования</p>
										</div>
									<?php } ?>
									<?php if (!empty($primaries['sale_of_spare'])) { ?>
										<div class="about__item">
											<div class="about__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/about/5.svg" alt=""></div>
											<p class="about__item-title">Продажа запасных частей</p>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
							<!-- /.about-box -->
						</div>
						<!-- /.about-box -->
					</div>
				<?php } ?>
				<!-- /.about -->

				<?php if (($firms = get_the_terms(get_the_ID(), 'firms')) && !empty($firms)) { ?>
					<div class="section_m section_white brands-served brands-more">
						<p class="title brands-served__title">Обслуживаемые бренды</p>
						<!-- /.brands-served__title title -->
						<div class="brands-served-box">
							<?php foreach ($firms as $firm) { ?>
								<div class="brands-served__item"><a href="<?= getFirmsPermalink($firm) ?>"><?= $firm->name ?></a></div>
							<?php } ?>
						</div>
						<!-- /.brands-served-box -->
						<button class="brands-served__button">Показать ещё</button>
						<!-- /.brands-served__button -->
					</div>
				<?php } ?>
				<!-- /.brands-served -->
				<?php if (($categories = get_the_terms(get_the_ID(), 'categories')) && !empty($categories)) { ?>
					<div class="section_m section_white brands-served service-technics-more">
						<p class="title brands-served__title">Ремонтируем технику</p>
						<!-- /.brands-served__title title -->
						<div class="brands-served-box">
							<?php foreach ($categories as $category) { ?>
								<div class="brands-served__item"><a href="<?= getCategoriesPermalink($category) ?>"><?= $category->name ?></a></div>
							<?php } ?>
						</div>
						<!-- /.brands-served-box -->
						<button class="brands-served__button">Показать ещё</button>
						<!-- /.brands-served__button -->
					</div>
				<?php } ?>
				<!-- /.brands-served -->

				<?php if ($faq = get_field('faq')) { ?>
					<div class="faq">
						<p class="faq__title title">Вопросы и ответы</p>
						<!-- /.faq__title -->
						<div class="faq-box">
							<?php foreach ($faq as $item) { ?>
								<div class="faq__item">
									<p class="faq__item-title"><?= $item['question'] ?></p>
									<div class="faq__item-answer">
										<p class="faq__item-text"><?= $item['answer'] ?></p>
									</div>
								</div>
							<?php } ?>
						</div>
						<!-- /.faq-box -->
					</div>
					<!-- /.faq -->
				<?php } ?>
			</div>
			<div class="service-info">
				<div class="mobile service-titleBx">
					<div class="service-titleBx__item">
						<span class="service__icon">
							<img src="<?= get_template_directory_uri() ?>/images/service/check.svg" alt="">
						</span>
						<span class="service__title"><?= get_the_title() ?></span>
					</div>
					<div class="service-titleBx__item">
						<span class="service__star">
							<img src="<?= get_template_directory_uri() ?>/images/service/star.svg" alt="">
						</span>
						<span class="service__rate">5.0 (15)</span>
					</div>
				</div>

				<?php if ($is_open !== null) { ?>
					<?php if ($is_open === true) { ?>
						<div class="service-info__open">Открыто до <?= $open_to ?></div>
					<?php } else { ?>
						<div class="service-info__open">Закрыто</div>
					<?php } ?>
				<?php } ?>

				<div class="service-info-box">
					<div class="service-info__item">
						<div class="service-info__item-svgBx">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_120_5162)">
									<path d="M9.99935 18.3327C14.6017 18.3327 18.3327 14.6017 18.3327 9.99935C18.3327 5.39698 14.6017 1.66602 9.99935 1.66602C5.39698 1.66602 1.66602 5.39698 1.66602 9.99935C1.66602 14.6017 5.39698 18.3327 9.99935 18.3327Z" stroke="#0D2938" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M10 5V10L13.3333 11.6667" stroke="#0D2938" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</g>
								<defs>
									<clipPath id="clip0_120_5162">
										<rect width="20" height="20" fill="white" />
									</clipPath>
								</defs>
							</svg>
						</div>
						<div class="service-info__item-content">
							Пн-Пт: <?= $weekdays ?> <br>
							Сб-Вс: <?= $weekend ?>
						</div>
					</div>

					<?php if (!empty($city) || !empty($address)) { ?>
						<div class="service-info__item">
							<div class="service-info__item-svgBx">
								<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17.5 8.3335C17.5 14.1668 10 19.1668 10 19.1668C10 19.1668 2.5 14.1668 2.5 8.3335C2.5 6.34437 3.29018 4.43672 4.6967 3.0302C6.10322 1.62367 8.01088 0.833496 10 0.833496C11.9891 0.833496 13.8968 1.62367 15.3033 3.0302C16.7098 4.43672 17.5 6.34437 17.5 8.3335Z" stroke="#0D2938" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M10 10.8335C11.3807 10.8335 12.5 9.71421 12.5 8.3335C12.5 6.95278 11.3807 5.8335 10 5.8335C8.61929 5.8335 7.5 6.95278 7.5 8.3335C7.5 9.71421 8.61929 10.8335 10 10.8335Z" stroke="#0D2938" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</div>
							<div class="service-info__item-content service-info__item-address">
								г.<?= $city->name ?>, <?= $address ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<? /*
				<div class="service-info__address">Проспект Ветеранов</div>
				*/ ?>

				<?php if (!empty($mail) || !empty($site)) { ?>
					<div class="service-info-data">
						<?php if (!empty($mail)) { ?>
							<a href="mailto: <?= $mail ?>" class="service-info-data__item"><img src="<?= get_template_directory_uri() ?>/images/service/mail.svg" alt=""><span><?= $mail ?></span></a>
						<?php } ?>
						<?php if (!empty($site)) { ?>
							<a href="mailto: <?= $site ?>" class="service-info-data__item"><img src="<?= get_template_directory_uri() ?>/images/service/globe.svg" alt=""><span><?= $site ?></span></a>
						<?php } ?>
					</div>
				<?php } ?>
				<!-- /.service-info-data -->
				<p class="service-info__date">
					Дата добавления: <?= get_the_date('d.m.Y') ?>
				</p>
				<!-- /.service__date -->
				<div class="service-info-buttonBx">
					<button class="pb service-info__button" data-type="repair">
						<span>
							<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M18 12.5C18 12.942 17.8244 13.366 17.5118 13.6785C17.1993 13.9911 16.7754 14.1667 16.3333 14.1667H6.33333L3 17.5V4.16667C3 3.72464 3.17559 3.30072 3.48816 2.98816C3.80072 2.67559 4.22464 2.5 4.66667 2.5H16.3333C16.7754 2.5 17.1993 2.67559 17.5118 2.98816C17.8244 3.30072 18 3.72464 18 4.16667V12.5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<span data-type="repair">Оставить заявку</span>
					</button>
					<?php if ($phones = get_field('phones')) { ?>
						<?php foreach ($phones as $phone) { ?>
							<a href="tel: <?= phoneLink($phone['number']) ?>" class="service-info__button">
								<span>
									<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<g clip-path="url(#clip0_120_5152)">
											<path d="M13.0415 4.16683C13.8555 4.32563 14.6035 4.72371 15.1899 5.31011C15.7763 5.89651 16.1744 6.64455 16.3332 7.4585M13.0415 0.833496C14.7326 1.02136 16.3095 1.77864 17.5134 2.981C18.7173 4.18336 19.4765 5.75934 19.6665 7.45016M18.8332 14.1002V16.6002C18.8341 16.8322 18.7866 17.062 18.6936 17.2746C18.6006 17.4873 18.4643 17.6782 18.2933 17.8351C18.1222 17.9919 17.9203 18.1114 17.7005 18.1858C17.4806 18.2601 17.2477 18.2877 17.0165 18.2668C14.4522 17.9882 11.989 17.112 9.82486 15.7085C7.81139 14.4291 6.10431 12.722 4.82486 10.7085C3.41651 8.5345 2.54007 6.05933 2.26653 3.4835C2.2457 3.25305 2.27309 3.0208 2.34695 2.80152C2.4208 2.58224 2.53951 2.38074 2.6955 2.20985C2.8515 2.03896 3.04137 1.90242 3.25302 1.80893C3.46468 1.71544 3.69348 1.66705 3.92486 1.66683H6.42486C6.82929 1.66285 7.22136 1.80606 7.528 2.06977C7.83464 2.33349 8.03493 2.6997 8.09153 3.10016C8.19705 3.90022 8.39274 4.68577 8.67486 5.44183C8.78698 5.7401 8.81125 6.06426 8.74479 6.3759C8.67832 6.68754 8.52392 6.97359 8.29986 7.20016L7.24153 8.2585C8.42783 10.3448 10.1552 12.0722 12.2415 13.2585L13.2999 12.2002C13.5264 11.9761 13.8125 11.8217 14.1241 11.7552C14.4358 11.6888 14.7599 11.713 15.0582 11.8252C15.8143 12.1073 16.5998 12.303 17.3999 12.4085C17.8047 12.4656 18.1744 12.6695 18.4386 12.9814C18.7029 13.2933 18.8433 13.6915 18.8332 14.1002Z" stroke="#0D2938" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
										</g>
										<defs>
											<clipPath id="clip0_120_5152">
												<rect width="20" height="20" fill="white" transform="translate(0.5)" />
											</clipPath>
										</defs>
									</svg>
								</span>
								<span><?= $phone['number'] ?></span>
							</a>
						<?php } ?>
					<?php } ?>
				</div>
				<!-- /.service-info-buttonBx -->
			</div>
		</div>
		<!-- /.service-inner -->
	</div>
	<!-- /.service__container -->
</section>
<!-- /.service -->

<?php get_footer() ?>