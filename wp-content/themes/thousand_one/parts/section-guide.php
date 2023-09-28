<?php
$cities = get_terms([
	'taxonomy' => 'city',
]);

$city_names = [];

foreach ($cities as $city) {
	$first_letter = strtoupper(mb_substr($city->name, 0, 1));
	$city_names[$first_letter][$city->term_id] = $city->name;
}

$cities_gerb = get_terms([
	'taxonomy' => 'city',
	'hide_empty' => false,
	'meta_key' => 'gerb',
]);

if (empty($cities)) return false;
?>
<section class="guide">
	<div class="guide__container">
		<h2 class="title guide__title">Справочник лучших сервисных центров</h2>
		<!-- /.guide__title title -->

		<?php if (!empty($cities_gerb)) ?>
		<div class="guide-gerbs">
			<?php foreach ($cities_gerb as $city_gerb) { ?>
				<a href="<?= getCitiesPermalink($city_gerb->term_id) ?>" class="guide-gerbs__item">

					<div class="guide-gerbs__item-imgBx"><?= wp_get_attachment_image(get_field('gerb', $city_gerb)) ?></div>
					<p class="guide-gerbs__item-city"><?= $city_gerb->name ?></p>
				</a>
			<?php } ?>
		</div>
		<!-- /.guide-gerbs -->
		<button class="brands__button js-button-city">Показать список городов</button>
		<!-- /.brands__button -->
		<div class="guide-wrapper">
			<div class="guide-inner">
				<?php foreach ($city_names as $frst_l => $c_names) { ?>
					<div class="guide__item">
						<p class="guide__item-symbol"><?= $frst_l ?></p>
						<ul class="guide__item-list">
							<?php foreach ($c_names as $term_id => $c_name) { ?>
								<li><a href="<?= getCitiesPermalink($term_id) ?>"><?= $c_name ?></a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
				<!-- /.guide-wrapper -->
				<img src="<?= get_template_directory_uri() ?>/images/guide/map.svg" alt="" class="guide__bg">
			</div>
		</div>
		<!-- /.guide-inner -->
	</div>
	<!-- /.guide__container -->
</section>
<!-- /.guide -->