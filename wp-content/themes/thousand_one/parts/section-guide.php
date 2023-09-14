<?php
$cities = get_terms([
	'taxonomy' => 'city',
]);

$city_names = [];

foreach ($cities as $city) {
	$first_letter = strtoupper(mb_substr($city->name, 0, 1));
	$city_names[$first_letter][$city->term_id] = $city->name;
}

if (empty($cities)) return false;
?>
<section class="guide">
	<div class="guide__container">
		<h2 class="title guide__title">Справочник лучших сервисных центров</h2>
		<!-- /.guide__title title -->
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
		<!-- /.guide-inner -->
	</div>
	<!-- /.guide__container -->
</section>
<!-- /.guide -->