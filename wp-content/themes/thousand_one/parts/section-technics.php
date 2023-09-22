<?php
$categories = get_terms([
	'taxonomy' => 'categories',
]);
$iteration = (isset($_GET['cat_iteration'])) ? $_GET['cat_iteration'] : 1;
$iteration--;
$step = 12;
$start = $step * $iteration;
$end = $start + $step - 1;

if (empty($categories)) return false;
?>

<section class="technics" data-more="cat">
	<div class="technics__container">
		<h2 class="title technics__title">Сервисы по видам техники</h2>
		<!-- /.technics__title title -->
		<div class="technics-inner">
			<div class="technics-box">
				<?php foreach ($categories as $key => $cat) { ?>
					<?php if ($key < $start) continue; ?>
					<a href="<?= getCategoriesPermalink($cat) ?>" class="technics__item">
						<div class="technics__item-imgBx">
							<?= kama_thumb_img('w=220&h=220&crop=0', getCategoryImageName($cat->term_id)) ?>
						</div>
						<p class="technics__item-title"><?= $cat->name ?></p>
					</a>
					<?php if ($key >= $end) break; ?>
				<?php } ?>
			</div>
			<!-- /.technics-box -->
			<?php if (count($categories) > ($end + 1)) { ?>
				<button class="technics__more" data-page="1">Показать еще</button>
			<?php } ?>
		</div>
	</div>
	<!-- /.technics__container -->
</section>
<!-- /.technics -->